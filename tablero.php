<?php
// INICIO PHP: sesión, helper y login obligatorio
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__.'/php/funciones_sesion.php';
exigir_login();

// INICIO PHP: obtención de usuario actual
$usuario_nombre = $_SESSION['nombre_usuario'] ?? 'Jugador';
$usuario_id     = (int)($_SESSION['id_usuario'] ?? 0);
?>
<!DOCTYPE html>
<!-- INICIO HTML: documento y metadatos -->
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Draftosaurus · Tablero</title>

  <!--INICIO HEAD: librerías y fuentes -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;800&display=swap"/>
  <link rel="stylesheet" href="css/styles.css"/>
  <link rel="stylesheet" href="css/tablero.css"/>

  <style>
    /* INICIO ESTILOS INLINE GENERALES */
    .contenedor-tablero img.tablero-img{ max-width:100%; height:auto }
    .caja-juego h6{ font-weight:700 }
    .dado{ font-size:3rem; transition: transform .6s ease; display:inline-block }
    .dado.animar{ transform: rotate(720deg) scale(1.2) }

    /* INICIO ESTILOS: Modal fin de partida */
    #fin-partida-backdrop{
      position:fixed; inset:0; display:none; align-items:center; justify-content:center;
      background:rgba(0,0,0,.55); backdrop-filter:blur(2px); z-index:4000;
    }
    .fin-card{
      width:min(92vw,640px); background:#fff; color:#111; border-radius:18px;
      box-shadow:0 25px 60px rgba(0,0,0,.35); padding:1.25rem 1.25rem 1rem;
      font-family:'Fredoka', system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    }
    .fin-card h2{ font-weight:800; text-align:center; margin:.25rem 0 1rem; font-size:clamp(1.9rem,4vw,2.4rem) }
    .fin-card h2.gano{ color:#16a34a }
    .fin-card .sub{ text-align:center; color:#444; margin:-.25rem 0 1rem }
    .fin-card table{ width:100%; border-collapse:collapse; font-size:1rem }
    .fin-card th, .fin-card td{ padding:.65rem .75rem; border-bottom:1px solid #eee }
    .fin-card th{ color:#555; text-transform:uppercase; font-weight:700; font-size:.85rem }
    .fin-card tr.highlight{ background:#f8fafc }
    .fin-card .acciones{ display:flex; gap:.75rem; justify-content:center; margin-top:1rem }
    .fin-card .btn{ border:0; border-radius:12px; padding:.7rem 1rem; font-weight:700; cursor:pointer; box-shadow:0 6px 18px rgba(0,0,0,.12) }
    .fin-card .btn-prim{ background:#111827; color:#fff } .fin-card .btn-sec{ background:#f3f4f6; color:#111827 }
    .dino-seleccionado {
      outline: 3px solid #ffc107;
      outline-offset: 3px;
      border-radius: 8px;
}
  </style>
</head>

<body class="d-flex flex-column min-vh-100 con-fondo">
  <!-- INICIO HEADER/NAV SUPERIOR -->
  <header class="navbar navbar-expand-lg jungle-header sticky-top">
    <div class="container px-4 d-flex justify-content-between align-items-center">
      <a class="navbar-brand text-white fs-3" href="menu.php">Draftosaurus</a>
      <a class="btn btn-safari fw-bold" href="menu.php">Salir</a>
    </div>
  </header>

  <!-- INICIO MAIN: layout principal -->
  <main class="flex-grow-1 d-flex flex-column justify-content-center text-white px-4 py-4 mt-2 overflow-hidden">
    <section class="container-fluid">
      <div class="row g-4 align-items-start justify-content-center">
        <!-- INICIO PANEL PUNTUACIÓN (ASIDE) -->
        <aside class="col-md-2 d-none d-md-block">
          <article class="caja-juego text-center">
            <h6 class="mb-2">Puntuación</h6>
            <ul class="list-unstyled mt-3" id="lista-puntuacion">
              <li>
                <?php echo htmlspecialchars($usuario_nombre); ?>:
                <span class="badge bg-warning" id="puntos-0">0 pts</span>
              </li>
            </ul>
          </article>
        </aside>

        <!-- INICIO TABLERO DE JUEGO -->
        <section class="col-12 col-md-8 text-center contenedor-tablero">
          <div class="marco-tablero position-relative">
            <img src="img/tablero.png" alt="Tablero del juego" class="img-fluid rounded shadow-lg tablero-img"/>
            <!-- INICIO ZONAS DE DROP / RECINTOS -->
            <div class="zona-drop" data-recinto="bosque" data-lado="izquierda" data-terreno="bosque" style="left:8%; top:8%; width:33%; height:28%;"></div>
            <div class="zona-drop" data-recinto="prado"  data-lado="izquierda" data-terreno="rocas"  style="left:8%; top:40%; width:35%; height:24%;"></div>
            <div class="zona-drop" data-recinto="amor"   data-lado="derecha"   data-terreno="bosque" style="left:55%; top:8%; width:30%; height:26%;"></div>
            <div class="zona-drop" data-recinto="trio"   data-lado="izquierda" data-terreno="rocas"  style="left:8%; top:68%; width:35%; height:24%;"></div>
            <div class="zona-drop" data-recinto="rey"    data-lado="derecha"   data-terreno="bosque" style="left:55%; top:40%; width:30%; height:26%;"></div>
            <div class="zona-drop" data-recinto="isla"   data-lado="derecha"   data-terreno="rocas"  style="left:70%; top:75%; width:22%; height:18%;"></div>
            <div class="zona-drop" data-recinto="rio"    data-lado="centro"    data-terreno="agua"   style="left:47.5%; top:10%; width:5%; height:80%;"></div>
          </div>
        </section>

        <!-- INICIO DADO (ESCRITORIO) -->
        <aside class="col-md-2 d-none d-md-block">
          <article class="caja-juego text-center">
            <h6 class="mb-2">Dado</h6>
            <div id="dado-escritorio" class="dado">🎲</div>
            <div id="texto-restriccion" class="mt-2 small text-dark"></div>
            <button id="btn-tirar-escritorio" class="btn btn-safari mt-3" onclick="tirarDado('escritorio')">Tirar</button>
          </article>
        </aside>
      </div>

      <!-- INICIO DADO (MÓVIL)-->
      <section class="d-md-none text-center mt-4">
        <article class="caja-juego mx-auto" style="max-width: 300px;">
          <h6 class="mb-2">Dado</h6>
          <div id="dado-movil" class="dado">🎲</div>
          <div id="texto-restriccion-movil" class="mt-2 small text-dark"></div>
          <button id="btn-tirar-movil" class="btn btn-safari mt-3" onclick="tirarDado('movil')">Tirar</button>
        </article>
      </section>

      <!-- INICIO MANO DE DINOSAURIOS -->
      <section class="caja-juego text-center mx-auto mt-4" style="max-width: 700px;">
        <h5>Dinosaurios disponibles</h5>
        <div id="mano-dinos" class="d-flex justify-content-center flex-wrap gap-3 mt-2"></div>
      </section>
    </section>
  </main>

  <!-- INICIO MODAL DE FIN DE PARTIDA -->
  <div id="fin-partida-backdrop" aria-hidden="true">
    <div class="fin-card">
      <h2 id="fin-titulo" class="gano">¡FIN DE LA PARTIDA!</h2>
      <p id="fin-sub" class="sub">¡Bien jugado!</p>
      <div class="table-responsive">
        <table>
          <thead><tr><th style="text-align:left;">Jugador</th><th style="text-align:right;">Puntos</th></tr></thead>
          <tbody id="fin-tbody"></tbody>
        </table>
      </div>
      <div class="acciones">
        <button class="btn btn-prim" onclick="location.href='menu.php'">Volver al menú</button>
        <button class="btn btn-sec" onclick="location.reload()">Jugar de nuevo</button>
      </div>
    </div>
  </div>

  <!-- INICIO FOOTER -->
  <footer class="text-white text-center py-3">
    <p class="m-0">Proyecto Draftosaurus © 2025</p>
  </footer>

  <!-- INICIO LÓGICA DEL JUEGO: JavaScript -->
  <script>
  // INICIO CONFIGURACIÓN Y ESTADO GLOBAL
  const JUGADOR = <?php echo json_encode($usuario_nombre); ?>;
  const USUARIO_ID = <?php echo (int)$usuario_id; ?>;
  let dinoSeleccionado = null; 
  const ES_MOVIL = window.matchMedia('(pointer: coarse)').matches; 
  
  if (ES_MOVIL) {
  document.addEventListener('touchmove', function (e) {
    if (dinoSeleccionado) e.preventDefault();
  }, { passive: false });
}


  let ronda = 1;               
  let turno = 1;               
  let startedAt = Math.floor(Date.now() / 1000);

  let mano = [];               
  let bolsa = [];              

  let restriccionActual = null; 
  let humanoDebeColocar = false;

  const estado = { ocupacion:{ bosque:[], prado:[], amor:[], trio:[], rey:[], isla:[], rio:[] } };

  const META_RECINTOS = {
    bosque:{lado:'izquierda', terreno:'bosque'},
    prado :{lado:'izquierda', terreno:'rocas'},
    amor  :{lado:'derecha',   terreno:'bosque'},
    trio  :{lado:'izquierda', terreno:'rocas'},
    rey   :{lado:'derecha',   terreno:'bosque'},
    isla  :{lado:'derecha',   terreno:'rocas'},
    rio   :{lado:'centro',    terreno:'agua'}
  };

  // INICIO LISTA DE RESTRICCIONES DEL DADO/COLOCACIÓN
  const RESTRICCIONES = [
    { id:'lado_izq',       texto:'Colocar en la zona IZQUIERDA del parque' },
    { id:'lado_der',       texto:'Colocar en la zona DERECHA del parque' },
    { id:'terreno_bosque', texto:'Colocar en zona BOSCOSA' },
    { id:'terreno_rocas',  texto:'Colocar en zona de ROCAS' },
    { id:'recinto_vacio',  texto:'Colocar en un RECINTO VACÍO' },
    { id:'sin_trex',       texto:'Colocar en RECINTO SIN T-Rex' },
  ];

  // INICIO CATÁLOGO LOCAL DE ESPECIES 
  let ESPECIES = [
    { id:'trex',          nombre:'T-Rex',         img:'img/dinos/trex.png' },
    { id:'triceratops',   nombre:'Triceratops',   img:'img/dinos/triceratops.png' },
    { id:'brachiosaurio', nombre:'Brachiosaurio', img:'img/dinos/brachiosaurio.png' },
    { id:'estegosaurio',  nombre:'Estegosaurio',  img:'img/dinos/estegosaurio.png' },
    { id:'raptor',        nombre:'Raptor',        img:'img/dinos/raptor.png' },
    { id:'parasaurolofo', nombre:'Parasaurolofo', img:'img/dinos/parasaurolofo.png' },
  ];

  // INICIO FUNCIONES UI HELPERS
  function setBotonDadoHabilitado(h){
    const b1 = document.getElementById('btn-tirar-escritorio'); if (b1) b1.disabled = !h;
    const b2 = document.getElementById('btn-tirar-movil');      if (b2) b2.disabled = !h;
  }
  function pintarRestriccion(){
    const txt = restriccionActual ? 'Restricción: ' + restriccionActual.texto : 'Debe tirar el dado';
    ['texto-restriccion','texto-restriccion-movil'].forEach(id=>{
      const el = document.getElementById(id); if (el) el.textContent = txt;
    });
  }
  function actualizarMarcadorJugador(puntos){
    const badge = document.getElementById('puntos-0');
    if (badge) badge.textContent = `${puntos} pts`;
  }

  // INICIO CÁLCULO DE PUNTUACIÓN POR RECINTO
  const PUNTOS_BOSQUE = [0,0,3,6,10,15,21];
  const PUNTOS_PRADO  = [0,0,3,6,10,15];

  function puntuarBosque(lista){
    const n = Math.min(lista.length, PUNTOS_BOSQUE.length - 1);
    const allSame = lista.every(e => e === lista[0]);
    return allSame ? PUNTOS_BOSQUE[n] : 0;
  }
  function puntuarPrado(lista){
    const set = new Set(lista);
    if (set.size !== lista.length) return 0;
    const n = Math.min(lista.length, PUNTOS_PRADO.length - 1);
    return PUNTOS_PRADO[n];
  }
  function puntuarAmor(lista){
    const map = lista.reduce((m,e)=>(m[e]=(m[e]||0)+1, m),{});
    let parejas = 0; for (const k in map) parejas += Math.floor(map[k]/2);
    return parejas * 5;
  }
  function puntuarTrio(lista){ return (lista.length === 3) ? 7 : 0; }
  function puntuarRey(lista){  return (lista.length === 1) ? 7 : 0; } // single
  function puntuarIsla(lista){
    if (lista.length !== 1) return 0;
    const todas = Object.values(estado.ocupacion).flat();
    const especie = lista[0];
    const count = todas.filter(e => e === especie).length;
    return (count === 1) ? 7 : 0;
  }
  function puntuarRio(lista){ return lista.length * 1; }
  function bonusTRexPorRecinto(lista){ return lista.includes('trex') ? 1 : 0; }

  function calcularPuntosJugador(ocup){
    const t = {};
    t.bosque = puntuarBosque(ocup.bosque) + bonusTRexPorRecinto(ocup.bosque);
    t.prado  = puntuarPrado(ocup.prado)   + bonusTRexPorRecinto(ocup.prado);
    t.amor   = puntuarAmor(ocup.amor)     + bonusTRexPorRecinto(ocup.amor);
    t.trio   = puntuarTrio(ocup.trio)     + bonusTRexPorRecinto(ocup.trio);
    t.rey    = puntuarRey(ocup.rey)       + bonusTRexPorRecinto(ocup.rey);
    t.isla   = puntuarIsla(ocup.isla)     + bonusTRexPorRecinto(ocup.isla);
    t.rio    = puntuarRio(ocup.rio);
    const total = Object.values(t).reduce((a,b)=>a+b,0);
    return { total, detalle:t };
  }

  // INICIO DADO Y APLICACIÓN DE RESTRICCIONES
  function tirarDado(device){
    const id = (device==='movil') ? 'dado-movil' : 'dado-escritorio';
    const dado = document.getElementById(id); if (!dado) return;
    if (restriccionActual) return; // ya hay restricción este turno

    dado.classList.add('animar');
    const idx = Math.floor(Math.random()*6);
    setTimeout(()=>{
      dado.textContent = String(idx+1);
      dado.classList.remove('animar');
      restriccionActual = RESTRICCIONES[idx];
      pintarRestriccion();
      humanoDebeColocar = true;
      setBotonDadoHabilitado(false);
    },600);
  }

  // INICIO VALIDACIÓN DE RESTRICCIÓN ACTUAL
  function validaRestriccion(especie, zonaEl){
    if (!restriccionActual) return true;
    const r = restriccionActual.id;
    const lado = zonaEl.dataset.lado, terr = zonaEl.dataset.terreno, recinto = zonaEl.dataset.recinto;

    if (r==='lado_izq' && lado!=='izquierda') return false;
    if (r==='lado_der' && lado!=='derecha')   return false;
    if (r==='terreno_bosque' && terr!=='bosque') return false;
    if (r==='terreno_rocas'  && terr!=='rocas')  return false;
    if (r==='recinto_vacio' && (estado.ocupacion[recinto]||[]).length>0) return false;
    if (r==='sin_trex' && (estado.ocupacion[recinto]||[]).some(e=>e==='trex')) return false;
    return true;
  }
  function existeZonaValidaParaRestriccion(especie){
  return Array.from(document.querySelectorAll('.zona-drop'))
    .some(z => validaRestriccion(especie, z));
}


  // INICIO VALIDACIÓN DE REGLAS POR RECINTO
  function validarReglasRecinto(recinto, especie){
    const lista = estado.ocupacion[recinto] || [];
    switch(recinto){
      case 'bosque': {
        if (lista.length===0) return {ok:true};
        const base = lista[0];
        return {ok: lista.every(e=>e===base) && especie===base, motivo:'Bosque: deben ser todos de la MISMA especie.'};
      }
      case 'prado': return {ok: !lista.includes(especie), motivo:'Prado: todas las especies deben ser DIFERENTES.'};
      case 'amor':  return {ok:true};
      case 'trio':  return {ok: lista.length<3, motivo:'Trío: máximo 3 dinosaurios.'};
      case 'rey':
      case 'isla':  return {ok: lista.length<1, motivo:'Sólo 1 dinosaurio en este recinto.'};
      case 'rio':
      default:      return {ok:true};
    }
  }

  // INICIO DRAG & DROP: colocación de fichas
  function prepararZonasDrop(){
  document.querySelectorAll('.zona-drop').forEach(z=>{
    // PC: drag & drop
    z.addEventListener('dragover', e=>{ e.preventDefault(); z.classList.add('sobre'); });
    z.addEventListener('dragleave', ()=> z.classList.remove('sobre'));
    z.addEventListener('drop', async e=>{
      e.preventDefault(); z.classList.remove('sobre');

      if (!restriccionActual) { console.warn('Primero tirá el dado.'); return; }
      if (!humanoDebeColocar) { console.warn('Ya colocaste este turno.'); return; }

      let especie=null, indice=null;
      try{
        const obj = JSON.parse(e.dataTransfer.getData('text/plain'));
        especie = obj.especie; indice = parseInt(obj.indice,10);
      }catch(_){ return; }
      if (!especie || Number.isNaN(indice)) return;

      // RESTRICCIÓN
      if (!validaRestriccion(especie, z)){
        if (existeZonaValidaParaRestriccion(especie)){
          console.warn('No podés colocar acá por la restricción.');
          return;
        }
        
      }

      // REGLA DEL RECINTO
      const rec = z.dataset.recinto;
      const vr = validarReglasRecinto(rec, especie);
      if (!vr.ok){
        const rio = document.querySelector('.zona-drop[data-recinto="rio"]');
        if (!rio){ console.warn(vr.motivo); return; }
        colocarDinoEnZona(especie, indice, rio, e);
      } else {
        colocarDinoEnZona(especie, indice, z, e);
      }

      // ACTUALIZAR
      if (!Number.isNaN(indice)) mano.splice(indice,1);
      renderMano(mano);
      const { total } = calcularPuntosJugador(estado.ocupacion);
      actualizarMarcadorJugador(total);

      humanoDebeColocar = false;
      restriccionActual = null;
      pintarRestriccion();
      await avanzarTurno();
    });

    // MÓVIL: tap sobre recinto (tap-tap)
    z.addEventListener('click', async e=>{
      if (!ES_MOVIL) return; 
      if (!restriccionActual) { console.warn('Primero tirá el dado.'); return; }
      if (!humanoDebeColocar) { console.warn('Ya colocaste este turno.'); return; }
      if (!dinoSeleccionado)  { console.warn('Elegí un dinosaurio primero.'); return; }

      const especie = dinoSeleccionado.especie;
      const indice  = dinoSeleccionado.indice;

      // RESTRICCIÓN
      if (!validaRestriccion(especie, z)){
        if (existeZonaValidaParaRestriccion(especie)){
          console.warn('No podés colocar acá por la restricción.');
          return;
        }
      }

      // REGLA DEL RECINTO
      const rec = z.dataset.recinto;
      const vr = validarReglasRecinto(rec, especie);
      if (!vr.ok){
        const rio = document.querySelector('.zona-drop[data-recinto="rio"]');
        if (!rio){ console.warn(vr.motivo); return; }
        colocarDinoEnZona(especie, indice, rio, e);
      } else {
        colocarDinoEnZona(especie, indice, z, e);
      }

      // ACTUALIZAR
      if (!Number.isNaN(indice)) mano.splice(indice,1);
      renderMano(mano);
      const { total } = calcularPuntosJugador(estado.ocupacion);
      actualizarMarcadorJugador(total);

      humanoDebeColocar = false;
      restriccionActual = null;
      pintarRestriccion();
      dinoSeleccionado = null; 
      await avanzarTurno();
    });
  });
}


  // INICIO COLOCACIÓN FÍSICA EN ZONA
 function colocarDinoEnZona(especie, idx, zona, ev){
  const rect = zona.getBoundingClientRect();
  const cx = (ev && ev.clientX != null) ? ev.clientX : rect.left + rect.width/2;
  const cy = (ev && ev.clientY != null) ? ev.clientY : rect.top  + rect.height/2;
  const x = ((cx - rect.left)/rect.width)*100;
  const y = ((cy - rect.top )/rect.height)*100;

  const especieObj = ESPECIES.find(e=>e.id===especie) || ESPECIES[0];

  const ficha = document.createElement('img');
  ficha.src = especieObj.img; ficha.alt = especieObj.nombre;
  ficha.className = 'ficha-dino';
  ficha.style.left = `${x}%`; ficha.style.top = `${y}%`;
  zona.appendChild(ficha);

  const rec = zona.dataset.recinto;
  if (!estado.ocupacion[rec]) estado.ocupacion[rec]=[];
  estado.ocupacion[rec].push(especieObj.id);
}


  // INICIO AVANCE DE TURNOS Y RONDAS
  async function avanzarTurno(){
    turno++;
    if (turno>6){
      if (ronda===1){
        ronda = 2; turno = 1;
        mano = sacarDeBolsa(bolsa, 6);
        renderMano(mano);
        setBotonDadoHabilitado(true);
      } else {
        finalizarPartida();
      }
    } else {
      setBotonDadoHabilitado(true);
    }
  }

  // INICIO PANTALLA DE RESULTADO FINAL
  function mostrarResultadoFinal(puntos){
    const backdrop = document.getElementById('fin-partida-backdrop');
    const tbody = document.getElementById('fin-tbody');
    tbody.innerHTML = '';
    const tr = document.createElement('tr'); tr.classList.add('highlight');
    const tdN = document.createElement('td'); tdN.textContent = JUGADOR;
    const tdP = document.createElement('td'); tdP.style.textAlign='right'; tdP.textContent = puntos+' pts';
    tr.appendChild(tdN); tr.appendChild(tdP); tbody.appendChild(tr);
    backdrop.style.display = 'flex';
    backdrop.setAttribute('aria-hidden','false');
  }

  // INICIO FINALIZACIÓN Y GUARDADO DE PARTIDA
  async function finalizarPartida(){
    const { total, detalle } = calcularPuntosJugador(estado.ocupacion);
    try{
      await fetch('php/guardar_partida.php', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify({ usuario_id:USUARIO_ID, puntos:total, detalle, ocupacion:estado.ocupacion, startedAt })
      });
    }catch(e){ console.warn('No se pudo guardar:', e); }
    mostrarResultadoFinal(total);
  }

  // INICIO BOLSA Y MANO (sorteo de piezas)
  function construirBolsa(){
    const base = 12; const b=[];
    ESPECIES.forEach(esp=>{ for(let i=0;i<base;i++) b.push(esp.id); });
    return mezclar(b);
  }
  function mezclar(a){ a=a.slice(); for(let i=a.length-1;i>0;i--){ const j=Math.floor(Math.random()*(i+1)); [a[i],a[j]]=[a[j],a[i]]; } return a; }
  function sacarDeBolsa(b,n){ const m=[]; for(let i=0;i<n && b.length>0;i++) m.push(b.pop()); return m; }

  // INICIO RENDERIZADO DE LA MANO
  function renderMano(ids){
  const cont = document.getElementById('mano-dinos');
  cont.innerHTML = '';

  ids.forEach((id, idx) => {
    const esp = ESPECIES.find(e => e.id === id) || { img:'', nombre:id };
    const img = document.createElement('img');
    img.src = esp.img;
    img.alt = esp.nombre;
    img.title = esp.nombre;
    img.width = 64;
    img.height = 64;
    img.className = 'dino-img';
    img.dataset.indice = String(idx);
    img.dataset.especie = id;

    if (!ES_MOVIL) {
      img.draggable = true;
      img.addEventListener('dragstart', ev => {
        ev.dataTransfer.setData('text/plain', JSON.stringify({ indice: idx, especie: id }));
        ev.dataTransfer.setDragImage(img, 24, 24);
      });
    } else {
      img.draggable = false;
      img.addEventListener('click', () => {
        if (dinoSeleccionado && dinoSeleccionado.indice === idx) {
          dinoSeleccionado = null;
          img.classList.remove('dino-seleccionado');
          return;
        }
        cont.querySelectorAll('.dino-img').forEach(n => n.classList.remove('dino-seleccionado'));
        dinoSeleccionado = { indice: idx, especie: id };
        img.classList.add('dino-seleccionado');
      });
    }

    cont.appendChild(img);
  });
}


  //  CARGA catálogo de dinos
  async function cargarDinosaurios(){
    try{
      const r = await fetch('php/dinosaurios_listar.php');
      if (!r.ok) throw new Error('HTTP '+r.status);
      const data = await r.json();
      const mapa = {
        't-rex':'trex','trex':'trex','triceratops':'triceratops','brachiosaurio':'brachiosaurio',
        'estegosaurio':'estegosaurio','velociraptor':'raptor','raptor':'raptor','parasaurolofo':'parasaurolofo'
      };
      const toSlug = s => (s||'').toString().normalize('NFD').replace(/[\u0300-\u036f]/g,'').toLowerCase().replace(/\s+/g,'').replace(/-/g,'').trim();
      const tmp=[];
      for (const d of data){
        const slug = toSlug(d.especie);
        const key = Object.keys(mapa).find(k=>slug.includes(k.replace(/-/g,'')));
        const id  = key ? mapa[key] : null;
        if (!id) continue;
        const base = ESPECIES.find(e=>e.id===id);
        if (base && !tmp.some(x=>x.id===id)) tmp.push(base);
      }
      if (tmp.length>=4) ESPECIES = tmp;
    }catch(e){ }
  }

  // INICIO BOOT / ARRANQUE DE LA PARTIDA
  async function iniciarPartida(){
    await cargarDinosaurios();           
    bolsa = construirBolsa();
    mano  = sacarDeBolsa(bolsa, 6);
    renderMano(mano);
    prepararZonasDrop();
    restriccionActual = null;
    pintarRestriccion();
    setBotonDadoHabilitado(true);
    startedAt = Math.floor(Date.now()/1000);
    actualizarMarcadorJugador(0);
  }

  document.addEventListener('DOMContentLoaded', iniciarPartida);
  </script>

  <!-- INICIO LIBRERÍAS JS EXTERNAS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
