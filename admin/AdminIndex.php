<?php 
  include("../includes/NavbarAdmin.php");
  include("../config/Koneksi.php");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Admin - Kelola Jadwal Praktek</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
  /* ---------- Global ---------- */
  :root{
    --blue:#1a3c92;
    --yellow:#f7c948;
    --text:#1a237e;
  }
  *{box-sizing:border-box}
  body{
    margin:0;
    font-family: Arial, sans-serif;
    background:#f8f9fa;
    color:var(--text);
  }
  a{color:inherit;text-decoration:none}

  /* ---------- JADWAL ---------- */
  .judul-jadwal {
      background-color: #1a3c92;   /* biru tua */
      color: #fff;                 /* teks putih */
      padding: 15px 30px;                /* jarak dalam */
      border-radius: 40px;         /* sudut melengkung */
      text-align: center;          /* rata tengah */
      font-weight: bold;
      font-size: 30px;
      margin: 40px auto 20px auto; /* jarak luar */
      width: 90%;                  /* panjang bar */
    }
    .judul-jadwal h2 {
      margin: 0;  /* biar teks nempel tanpa jarak ekstra */
    }
    .jadwal {
      padding: 60px 80px;
      background-color: #f8f9fa;
      text-align: center;
    }
    .jadwal h2 {
      font-size: 28px;
      margin-bottom: 10px;
    }



  /* ---------- Cards grid ---------- */
    .jadwal-cards {
      display: flex;
      gap: 20px;
      justify-content: center;
      flex-wrap: wrap;
    }
    .card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      overflow: hidden;
      width: 320px;
      text-align: left;
      border-bottom: 6px solid #f7c948; /* garis kuning bawah */
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border-left: 6px solid #f7c948; /* garis kuning kiri */
    }
    .card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }
    .card:hover {
    transform: translateY(-10px); /* naik saat hover */
    box-shadow: 0 12px 20px rgba(0,0,0,0.2); /* bayangan lebih tebal */
    }
    .card-body {
      padding: 20px 20px 15px 20px;
    }
    .card-body h3 {
      font-size: 18px;
      margin-top: 1px;
      margin-bottom: 0;
    }
    .card-body p {
    margin-bottom: 20px; /* paksa hilang margin bawaan semua paragraf */
    }

    .alamat {
      font-size: 12px;
      margin-top: 3px;
      margin-bottom: 0px;
      color: #333;
      line-height: 1.2;
    }
  .card-body h3 i{ color:var(--yellow); font-size:22px; margin-left:2px }
  /* table */
      table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 0;
    }
    table th {
      background-color: #1a3c92;
      color: #f7c948;
      padding: 8px;
      text-align: left;
    }
    table td {
      border: 1px solid #ddd;
      padding: 8px;
    }
  /* edit button on card */
  .btn-edit{
    position:absolute;
    top:10px;
    right:10px;
    background:var(--blue);
    color:#fff;
    border:none;
    padding:6px 10px;
    border-radius:8px;
    cursor:pointer;
    font-size:13px;
  }

    /* tambah button — di bawah judul (kamu minta) */
  .btn-admin {
    display:inline-block;
    background:var(--yellow);
    color:var(--text);
    padding:10px 18px;
    border-radius:20px;
    font-weight:700;
    cursor:pointer;
    border:none;
    margin:14px 0 30px 0;
  }

  /* ---------- Modal ---------- */
  .modal{ display:none; position:fixed; inset:0; z-index:3000; background:rgba(0,0,0,0.55); align-items:center; justify-content:center; padding:20px; }
  .modal.show{ display:flex }
  .modal-content{
    width:680px; max-width:100%;
    background:#fff; border-radius:12px; padding:18px 20px 22px 20px; position:relative;
    box-shadow:0 10px 40px rgba(0,0,0,0.25);
  }
  .modal-content h2{ margin:0 0 10px 0; color:var(--blue); }
  .close-btn{ position:absolute; top:14px; right:16px; cursor:pointer; font-size:20px; color:#333 }

  .form-row{ display:flex; gap:12px; margin-bottom:10px; }
  .form-row .col{ flex:1 }
  input[type="text"], input[type="url"], textarea, input[type="file"]{
    width:100%; padding:8px 10px; border:1px solid #ccc; border-radius:6px; font-size:14px;
  }
  textarea{ min-height:70px; resize:vertical }

  .schedules-list{ margin:6px 0 8px 0; display:flex; flex-direction:column; gap:8px }
  .sched-row{ display:flex; gap:8px; align-items:center }
  .sched-row input{ padding:8px 10px; border-radius:6px; border:1px solid #ccc }
  .sched-row .remove-sched{ background:#e74c3c; color:#fff; border:none; padding:6px 8px; border-radius:6px; cursor:pointer }
  .add-sched{ display:inline-block; margin-top:6px; background:var(--blue); color:#fff; padding:8px 12px; border-radius:8px; border:none; cursor:pointer }

  .modal-actions{ display:flex; gap:10px; justify-content:flex-end; margin-top:10px }
  .btn-save{ background:var(--blue); color:#fff; padding:8px 14px; border-radius:8px; border:none; cursor:pointer }
  .btn-cancel{ background:#eee; color:#333; padding:8px 14px; border-radius:8px; border:none; cursor:pointer }

  /* responsive */
  @media (max-width:880px){
    .jadwal{ padding:20px 18px }
    .jadwal-cards{ gap:14px }
    .card{ width:100% }
    .modal-content{ width:100% }
  }
</style>
</head>
<body>

<section class="jadwal">
  <div class="judul-jadwal"><h2>Kelola Jadwal Praktek</h2></div>

  <!-- tombol tambah di bawah judul -->
  <button id="btnAdd" class="btn-admin" onclick="openModal('add')">+ Tambah Jadwal</button>

  <div class="jadwal-cards" id="cardsContainer">

    <!-- contoh card statis (data-schedules JSON disimpan di atribut data-schedules) -->
    <div class="card" data-id="1"
         data-nama="Rumah Sakit Unimus"
         data-alamat="Jl. Kedungmundu No.214, Tembalang, Kota Semarang"
         data-telp="0812345678910"
         data-link="https://www.google.com/maps/place/Rumah+Sakit+Unimus"
         data-gambar="RSUNIMUS.webp"
         data-schedules='[{"day":"Senin","time":"13:00 - 15:00"},{"day":"Selasa","time":"13:00 - 15:00"},{"day":"Rabu","time":"13:00 - 15:00"}]'>
      <button class="btn-edit" onclick="openModal('edit', this.closest('.card'))">Edit</button>

      <a class="card-link" href="https://www.google.com/maps/place/Rumah+Sakit+Unimus" target="_blank">
        <img src="assets/images/RSUNIMUS.webp" alt="RS Unimus">
        <div class="card-body">
          <h3><i class="fa-solid fa-location-dot"></i>Rumah Sakit Unimus</h3>
          <p class="alamat">Jl. Kedungmundu No.214, Tembalang, Kota Semarang<br>Telp: 0812345678910</p>
        </div>
      </a>

      <table>
        <thead><tr><th>Hari</th><th>Waktu</th></tr></thead>
        <tbody class="sched-table-body">
          <tr><td>Senin</td><td>13:00 - 15:00</td></tr>
          <tr><td>Selasa</td><td>13:00 - 15:00</td></tr>
          <tr><td>Rabu</td><td>13:00 - 15:00</td></tr>
        </tbody>
      </table>
    </div>

    <div class="card" data-id="2"
         data-nama="Rumah Sakit Kusuma Ungaran"
         data-alamat="Jl. Letjend Suprapto No.62, Ungaran"
         data-telp="081234567892"
         data-link="https://www.google.com/maps"
         data-gambar="RSKUSUMA.webp"
         data-schedules='[{"day":"Senin - Jumat","time":"18:00 - 21:00"}]'>
      <button class="btn-edit" onclick="openModal('edit', this.closest('.card'))">Edit</button>

      <a class="card-link" href="https://www.google.com/maps" target="_blank">
        <img src="assets/images/RSKUSUMA.webp" alt="RS Kusuma">
        <div class="card-body">
          <h3><i class="fa-solid fa-location-dot"></i>Rumah Sakit Kusuma Ungaran</h3>
          <p class="alamat">Jl. Letjend Suprapto No.62, Ungaran<br>Telp: 081234567892</p>
        </div>
      </a>

      <table>
        <thead><tr><th>Hari</th><th>Waktu</th></tr></thead>
        <tbody class="sched-table-body">
          <tr><td>Senin - Jumat</td><td>18:00 - 21:00</td></tr>
        </tbody>
      </table>
    </div>

  </div>
</section>

<!-- ========== Modal (Tambah/Edit) ========== -->
<div id="jadwalModal" class="modal" aria-hidden="true">
  <div class="modal-content" role="dialog" aria-modal="true">
    <span class="close-btn" onclick="closeModal()">&times;</span>
    <h2 id="modalTitle">Tambah Jadwal</h2>

    <form id="jadwalForm" onsubmit="handleSubmit(event)">
      <input type="hidden" id="cardId" value="">
      <div class="form-row">
        <div class="col">
          <label>Gambar (preview di UI):</label>
          <input id="inputGambar" type="file" accept="image/*">
        </div>
        <div class="col">
          <label>Nama Tempat:</label>
          <input id="inputNama" type="text" required placeholder="Nama rumah sakit / klinik">
        </div>
      </div>

      <div class="form-row">
        <div class="col">
          <label>Alamat:</label>
          <textarea id="inputAlamat" placeholder="Masukkan alamat lengkap"></textarea>
        </div>
        <div class="col">
          <label>No. Telp:</label>
          <input id="inputTelp" type="text" placeholder="0812xxxx">
        </div>
      </div>

      <div>
        <label>Jadwal (Hari & Waktu) — bisa tambah lebih dari 1:</label>
        <div class="schedules-list" id="schedulesList">
          <!-- rows akan ditambahkan dinamically -->
        </div>
        <button type="button" class="add-sched" onclick="addScheduleRow()">+ Tambah Baris Jadwal</button>
      </div>

      <div style="margin-top:12px">
        <label>Link Gmaps (area yang di-klik kartu):</label>
        <input id="inputLink" type="url" placeholder="https://maps.google.com/..." />
      </div>

      <div class="modal-actions">
        <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
        <button type="submit" class="btn-save">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
/* Utility: add schedule input row */
function addScheduleRow(day='', time=''){
  const list = document.getElementById('schedulesList');
  const row = document.createElement('div');
  row.className = 'sched-row';
  row.innerHTML = `
    <input class="sched-day" type="text" placeholder="Hari (mis: Senin atau Senin - Jumat)" value="${escapeHtml(day)}" required />
    <input class="sched-time" type="text" placeholder="Waktu (mis: 13:00 - 15:00)" value="${escapeHtml(time)}" required />
    <button type="button" class="remove-sched" title="Hapus" onclick="this.parentElement.remove()">✕</button>
  `;
  list.appendChild(row);
}

/* Modal control */
const modal = document.getElementById('jadwalModal');
function openModal(mode='add', cardElem=null){
  document.getElementById('modalTitle').innerText = mode === 'edit' ? 'Edit Jadwal' : 'Tambah Jadwal';
  document.getElementById('jadwalForm').reset();
  document.getElementById('schedulesList').innerHTML = '';
  document.getElementById('cardId').value = '';

  // default 1 row
  addScheduleRow();

  if(mode === 'edit' && cardElem){
    // isi form berdasarkan data atribut card
    const id = cardElem.dataset.id || '';
    document.getElementById('cardId').value = id;
    document.getElementById('inputNama').value = cardElem.dataset.nama || '';
    document.getElementById('inputAlamat').value = cardElem.dataset.alamat || '';
    document.getElementById('inputTelp').value = cardElem.dataset.telp || '';
    document.getElementById('inputLink').value = cardElem.dataset.link || '';

    // clear default row then populate from data-schedules
    document.getElementById('schedulesList').innerHTML = '';
    try {
      const sched = JSON.parse(cardElem.dataset.schedules || '[]');
      if(sched.length === 0) addScheduleRow();
      for(const s of sched) addScheduleRow(s.day || '', s.time || '');
    } catch(e){
      addScheduleRow();
    }
  }

  modal.classList.add('show');
  modal.setAttribute('aria-hidden','false');
}

/* close modal */
function closeModal(){
  modal.classList.remove('show');
  modal.setAttribute('aria-hidden','true');
}

/* handle form submit -> create or update card DOM */
function handleSubmit(e){
  e.preventDefault();

  const id = document.getElementById('cardId').value;
  const nama = document.getElementById('inputNama').value.trim();
  const alamat = document.getElementById('inputAlamat').value.trim();
  const telp = document.getElementById('inputTelp').value.trim();
  const link = document.getElementById('inputLink').value.trim();
  const fileInput = document.getElementById('inputGambar');

  // collect schedules
  const rows = Array.from(document.querySelectorAll('#schedulesList .sched-row'));
  const schedules = rows.map(r => {
    return {
      day: r.querySelector('.sched-day').value.trim(),
      time: r.querySelector('.sched-time').value.trim()
    };
  }).filter(s => s.day && s.time);

  // get image preview URL if file selected
  let imgSrc = '';
  if(fileInput && fileInput.files && fileInput.files[0]){
    imgSrc = URL.createObjectURL(fileInput.files[0]);
  }

  if(id){
    // update existing card with data-id == id
    const card = document.querySelector('.card[data-id="'+id+'"]');
    if(card){
      // update data attributes (store schedules as JSON)
      if(nama) card.dataset.nama = nama;
      card.dataset.alamat = alamat;
      card.dataset.telp = telp;
      card.dataset.link = link;
      if(imgSrc) card.dataset.gambar = imgSrc; // preview only
      card.dataset.schedules = JSON.stringify(schedules);

      // update visible elements
      const linkA = card.querySelector('.card-link');
      if(link) linkA.href = link;
      if(imgSrc) card.querySelector('img').src = imgSrc;
      card.querySelector('.card-body h3').childNodes.forEach(n=>{}); // nothing, keep icon
      card.querySelector('.card-body h3').lastChild && (card.querySelector('.card-body h3').lastChild.textContent = nama); // keep icon in first child
      // better: set text node after icon:
      const h3 = card.querySelector('.card-body h3');
      h3.innerHTML = '<i class="fa-solid fa-location-dot"></i>' + escapeHtml(nama);
      card.querySelector('.card-body .alamat').innerHTML = escapeHtml(alamat) + '<br>Telp: ' + escapeHtml(telp);

      // rebuild table body
      const tbody = card.querySelector('.sched-table-body');
      tbody.innerHTML = '';
      for(const s of schedules){
        const tr = document.createElement('tr');
        tr.innerHTML = `<td>${escapeHtml(s.day)}</td><td>${escapeHtml(s.time)}</td>`;
        tbody.appendChild(tr);
      }
    }
  } else {
    // create new card and append
    const newid = 'c' + Date.now();
    const cardData = {
      id:newid,
      nama:nama,
      alamat:alamat,
      telp:telp,
      link:link,
      gambar: imgSrc || 'placeholder.png',
      schedules:schedules
    };
    const el = createCardElement(cardData);
    document.getElementById('cardsContainer').appendChild(el);
  }

  // close modal
  closeModal();
}

/* create DOM element for card from data */
function createCardElement(d){
  const wrapper = document.createElement('div');
  wrapper.className = 'card';
  wrapper.dataset.id = d.id;
  wrapper.dataset.nama = d.nama;
  wrapper.dataset.alamat = d.alamat;
  wrapper.dataset.telp = d.telp;
  wrapper.dataset.link = d.link;
  wrapper.dataset.gambar = d.gambar;
  wrapper.dataset.schedules = JSON.stringify(d.schedules || []);

  // build inner HTML
  wrapper.innerHTML = `
    <button class="btn-edit" onclick="openModal('edit', this.closest('.card'))">Edit</button>
    <a class="card-link" href="${escapeAttr(d.link || '#')}" target="_blank">
      <img src="${escapeAttr(d.gambar || 'placeholder.png')}" alt="${escapeAttr(d.nama || '')}">
      <div class="card-body">
        <h3><i class="fa-solid fa-location-dot"></i>${escapeHtml(d.nama || '')}</h3>
        <p class="alamat">${escapeHtml(d.alamat || '')}<br>Telp: ${escapeHtml(d.telp || '')}</p>
      </div>
    </a>
    <table>
      <thead><tr><th>Hari</th><th>Waktu</th></tr></thead>
      <tbody class="sched-table-body"></tbody>
    </table>
  `;
  // populate table rows
  const tbody = wrapper.querySelector('.sched-table-body');
  (d.schedules||[]).forEach(s=>{
    const tr = document.createElement('tr');
    tr.innerHTML = `<td>${escapeHtml(s.day)}</td><td>${escapeHtml(s.time)}</td>`;
    tbody.appendChild(tr);
  });
  return wrapper;
}

/* small helpers to avoid XSS in demo */
function escapeHtml(str){ if(!str && str!==0) return ''; return String(str).replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;'); }
function escapeAttr(s){ return escapeHtml(s).replaceAll("'","&#39;"); }

/* ensure at least one schedule row exists on modal open default */
document.addEventListener('DOMContentLoaded', ()=>{
  // nothing: rows added on openModal
});

/* Close modal when click outside content */
modal.addEventListener('click', (e)=>{
  if(e.target === modal) closeModal();
});
</script>

</body>
</html>
