# ``[GET]`` /api/events

Retorna un arreglo de eventos.

## Ejemplo de retorno
```json
[
  {
    "id": 2,
    "title": "new title",
    "description": "new description",
    "location": "calle 10",
    "date": "2024-12-31",
    "capacity": 100,
    "created_at": "2024-11-20T12:54:51.000000Z",
    "updated_at": "2024-11-20T12:54:51.000000Z",
    "reservations": []
  }
]
```

# ``[GET]`` /api/events/daterange
Permite filtrar eventos por rango de fecha.

## Query
- ``start:`` Fecha de inicio.
- ``end:`` Fecha de fin.

## Ejemplo de retorno
```json 
[
  {
    "id": 2,
    "title": "new title",
    "description": "new description",
    "location": "calle 10",
    "date": "2024-12-31",
    "capacity": 100,
    "created_at": "2024-11-20T12:54:51.000000Z",
    "updated_at": "2024-11-20T12:54:51.000000Z",
    "reservations": []
  }
]
```

# ``[GET]`` /api/events/{event}
Retorna los datos de un evento

## Parámetros:
- ``event:`` Id del evento,

### Ejemplo de retorno

```json 
{
  "id": 2,
  "title": "new title",
  "description": "new description",
  "location": "calle 10",
  "date": "2024-12-31",
  "capacity": 100,
  "created_at": "2024-11-20T12:54:51.000000Z",
  "updated_at": "2024-11-20T12:54:51.000000Z"
}
```

# ``[POST]`` /api/events/{id}/reservation
Realiza una reservación.

## Parámetros
- ``id``: Id del evento en el que se desea reservar.

## Solicitud de ejemplo:
```json
{
  "username": "some_user",
  "email": "cstffx@gmail.com",
  "phone_number": "+5359496055"
}
```

## Respuesta
- ``[200]`` en caso de realizarse la reserva.
- ``[404]`` si no se encontró el evento.
- ``[200]`` en caso de realizarse la reserva.
- ``[422]`` en caso de error de validación.

# ``[GET]`` /api/events/{id}/availability
Retorna información de la disponibilidad de un evento.

## Parámetros
- ``id``: Id del evento en el que se desea consultar.

### Ejemplo de respuesta
```json
{
  "availability": 99,
  "message": "Hay 99 reservas disponibles"
}
```

# ``[PATCH]`` /api/events/{event}
Actualiza un evento existente.

## Contenido de solicitud:
```json
{
  "title": "new title",
  "description": "new description",
  "location": "calle 10",
  "date": "2024-12-31",
  "capacity": 100
}
```

## Parámetros:
- ``event``: Id del evento en el que se desea actualizar.

## Headers:
- ``Authorization:`` Token de autenticación en la forma ``Bearer`` + TOKEN.

# ``[POST]`` /api//event
Actualiza un evento existente.

## Contenido de solicitud:
```json
{
  "title": "new title",
  "description": "new description",
  "location": "calle 10",
  "date": "2024-12-31",
  "capacity": 100
}
```

## Parámetros:
- ``event``: Id del evento en el que se desea actualizar.

## Headers:
- ``Authorization:`` Token de autenticación en la forma ``Bearer`` + TOKEN.

# ``[DELETE]`` /events/{id}
Elimina un evento existente.

## Parámetros:
- ``id``: Id del evento en el que se desea actualizar.

## Headers:
- ``Authorization:`` Token de autenticación en la forma ``Bearer`` + TOKEN.
