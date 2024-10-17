# Simulador de Ball - StepMania Project

## Descripció

Aquest projecte consisteix en el desenvolupament d’un simulador de ball interactiu, inspirat en jocs rítmics populars com *StepMania* o *OSU!*. L'objectiu principal és oferir una experiència de joc dinàmica i divertida, on els usuaris poden seleccionar i reproduir cançons, seguint símbols o notes que cauen a la pantalla, les quals s’han de prémer en el moment adequat per obtenir la màxima puntuació possible.
### Característiques principals:

- **Afegir noves cançons**: Permet als usuaris pujar noves cançons i editar-les amb la seva pròpia música, caràtula, i les seqüències de joc.
- **Taula de classificació**: Guarda les millors puntuacions dels jugadors per cada cançó.
- **Funció de pausa**: Els jugadors poden pausar i reprendre la partida en qualsevol moment.
- **Sistema de puntuació dinàmica**: Les puntuacions augmenten o disminueixen en funció de si el jugador prem correctament o incorrectament les tecles.
- **Interfície amigable**: Una interfície clara i dinàmica amb disseny modern i responsiu.

## Requisits del sistema

Per poder executar l'aplicació, es necessita el següent programari:

- **PHP** 7.4 o superior
- **Navegador web modern** (Chrome, Firefox, Edge, etc.)
- **WireGuard** configurat si es vol executar a través d'una xarxa VPN (en cas d'entorns de producció específics)

## Instal·lació i configuració

### 1. Instal·lació en local

1. Clona el repositori:

    ```bash
    git clone https://github.com/usuari/projecte-stepmania.git
    ```

2. Navega a la carpeta del projecte:

    ```bash
    cd projecte-stepmania
    ```

3. Executa el servidor local PHP:

    ```bash
    php -S localhost:8000
    ```

4. Obre el teu navegador i visita `http://localhost:8000` per veure el projecte.

### 2. Entorn de producció (Servidor)

Si es vol desplegar en un entorn de producció, segueix aquests passos:

1. Pujar els fitxers al servidor a través de **FTP** o clonant el repositori.
2. Assegurar-te que el servidor té PHP i les seves extensions activades.
3. Configura la connexió a la xarxa utilitzant **WireGuard** seguint el manual (veure configuració detallada en els arxius adjunts).

## Estructura del projecte

- `/css/`: Conté tots els fitxers d'estils per a les diferents pàgines (index.css, jugar.css, etc.).
- `/js/`: Conté els fitxers JavaScript per gestionar la dinàmica del joc (jugar.js, index.js, etc.).
- `/php/`: Conté els scripts PHP per manejar la lògica del servidor (guardar puntuació, gestionar classificació, etc.).
- `/uploads/`: Conté les cançons, caràtules, i fitxers de joc que els usuaris han afegit.

## Com utilitzar l'aplicació

1. **Inici**: L'aplicació comença amb una pantalla on el jugador ha d'introduir el seu nom.
2. **Afegir cançons**: Els jugadors poden afegir noves cançons a través d'un formulari en l'opció de menú "Afegir".
3. **Jugar**: Un cop triada una cançó, els jugadors poden començar a jugar seguint les fletxes que cauen al ritme de la música.
4. **Classificació**: Després de cada joc, la puntuació es guarda i es mostra a la taula de classificacions.

## Crèdits

Aquest projecte ha estat desenvolupat per [David Peñalva Texeira]. 

