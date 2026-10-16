# Modelo de datos LigaHub

## Objetivo

La base de datos se ha preparado para sostener la API principal de LigaHub sin depender de cambios estructurales tardios. El modelo cubre competiciones, temporadas, categorias, equipos, jugadores, sedes, partidos y eventos de partido.

## Entidades principales

- `competitions`: representa una liga, copa o torneo. Agrupa temporadas y permite reutilizar la misma competicion varios anos.
- `categories`: separa divisiones como senior, sub-18, masculino, femenino o mixto.
- `seasons`: une competicion y categoria en un periodo concreto. Es el eje del calendario y de las plantillas.
- `venues`: almacena campos o estadios para equipos y partidos.
- `teams`: representa clubes o equipos participantes.
- `players`: almacena jugadores independientes de un equipo concreto, porque un jugador puede cambiar de equipo entre temporadas.
- `fixtures`: representa partidos programados o finalizados, con equipo local, visitante, jornada, sede y marcador.
- `match_events`: registra goles, tarjetas, asistencias u otros sucesos ligados a un partido, equipo y jugador.
- `player_team`: tabla pivote N:N entre jugadores y equipos con datos adicionales de plantilla por temporada.

## Relaciones

- Una `competition` tiene muchas `seasons`.
- Una `category` tiene muchas `seasons`.
- Una `season` tiene muchos `fixtures`.
- Un `venue` tiene muchos `teams` y muchos `fixtures`.
- Un `team` puede tener muchos `players` y un `player` puede pertenecer a muchos `teams`.
- La relacion `team`/`player` pasa por `player_team`, que guarda `season_id`, dorsal, posicion, fecha de alta, fecha de baja y si es capitan.
- Un `fixture` pertenece a una temporada, un equipo local, un equipo visitante y opcionalmente una sede.
- Un `fixture` tiene muchos `match_events`.

## Restricciones e indices

- Slugs unicos en competiciones, categorias y equipos para URLs/API.
- `seasons` impide duplicar la misma temporada dentro de una competicion y categoria.
- `fixtures` impide duplicar el mismo partido dentro de una jornada.
- `player_team` impide que un jugador este inscrito en dos equipos durante la misma temporada.
- `player_team` impide repetir dorsal dentro de un mismo equipo y temporada.
- Indices en estado, jornada, ciudad, nombres, fecha del partido y tipo de evento para consultas habituales de la API.

## Borrado logico

Se aplica `SoftDeletes` a entidades que pueden necesitar historico: competiciones, categorias, temporadas, sedes, equipos, jugadores y partidos. Los eventos de partido no usan borrado logico porque forman parte del acta de un partido y, si se borra el partido fisicamente, se eliminan en cascada.

## Datos de prueba

El seeder es reproducible:

- 1 usuario administrador.
- 1 competicion.
- 1 categoria.
- 1 temporada activa.
- 4 equipos con sede.
- 24 jugadores repartidos en plantillas.
- 4 partidos.
- 3 eventos de partido.

## Comprobacion rapida

```bash
php artisan migrate:fresh --seed
php artisan test --filter=DatabaseModelTest
```
