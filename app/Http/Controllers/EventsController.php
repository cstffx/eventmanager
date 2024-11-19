<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationPostRequest;
use App\Jobs\ReservationNotifyJob;
use App\Models\Events;
use App\Models\Reservations;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Validator;

const EVENT_VALIDATION = [
    'title' => 'required|string|max:255',
    'description' => 'required',
    'location' => 'required|string|max:255',
    'date' => 'required|date|after_or_equal:today',
    'capacity' => 'required|integer|min:1',
];

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Collection
    {
        return Events::with("reservations")->get();
    }

    /**
     * Display a listing of the resource.
     */
    public function daterange(Request $request): Collection
    {
        $start = $request->date("start");
        $end = $request->date("end");
        return Events::with("reservations")->dateRange($start, $end)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): void
    {
        $request->validate(EVENT_VALIDATION);
        Events::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Events $event): Events|null
    {
        return $event;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): void
    {
        $request->validate(EVENT_VALIDATION);
        $event = Events::find($id);
        $event->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): void
    {
        $event = Events::findOrFail($id);
        $event->delete();
    }

    /**
     * @param ReservationPostRequest $request
     * @param string $id
     * @return void
     */
    public function storeReservation(ReservationPostRequest $request,
                                     string                 $id): void
    {
        DB::transaction(function () use ($request, $id) {

            $email = $request->input("email");

            DB::query("LOCK TABLE reservations IN EXCLUSIVE");

            /** @var Events $event */
            $event = Events::findOrFail($id);

            // Transaction dependent validation.
            ValidatorFacade::make([], [])->after(function (Validator $validator) use ($event, $email) {
                if ($event->isFull()) {
                    // Rule: No more reservations than capacity.
                    $validator->errors()->add("event_id", trans("messages.event_limit"));
                    return;
                }

                // Rule: An email can't appear twice in the same event.
                $exists = !!($event->reservations()->where("email", $email)->count());
                if ($exists) {
                    $validator->errors()->add("email", trans("messages.email_already_exists", ['email' => $email]));
                }
            })->validate();

            $data = $request->validated();

            // Is ok to create the reservation.
            $reservation = Reservations::create([
                ...$data,
                'event_id' => $id
            ]);

            ReservationNotifyJob::dispatch($reservation);
        });
    }

    /**
     * @param Request $request
     * @param string $id
     * @return LengthAwarePaginator
     */
    public function reservations(Request $request, string $id): LengthAwarePaginator
    {
        /** @var Builder $baseQuery */
        $baseQuery = Reservations::where('event_id', $id);
        $username = $request->input('username');
        $pageSize = $request->integer("pageSize", 10);
        $username && $baseQuery->where('username', 'like', $username);
        $columns = [
            'id',
            'username',
            'phone_number',
            'email'
        ];
        return $baseQuery->orderBy('id')->paginate($pageSize, $columns);
    }

    public function availability(string $id): array
    {
        /** @var Events $event */
        $event = Events::findOrFail($id);
        $space = $event->getAvailableSpace();
        $message = trans_choice("messages.availability", $space, ['count' => $space]);
        return [
            'availability' => $space,
            'message' => $message
        ];
    }
}
