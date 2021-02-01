var zadaci = [];
var api_route = "http://localhost/todo/todo-back/api";
let timeout;

function citajZadatke() {
  return $.ajax({
    type: "GET",
    url: api_route + "/get_tasks.php",
    success: (result) => {
      zadaci = JSON.parse(result);
    },
  });
}

function prikaziZadatke() {
  let tabela_body = $("#tabela_svih_body");
  let tabela = [];
  zadaci.forEach((zadatak) => {
    let zavrsen_chk = "";
    let klasa_zavrsen = "";
    if (zadatak.zavrsen) {
      zavrsen_chk = "checked";
      klasa_zavrsen = "zavrsen";
    }
    let chk_box = `<input type="checkbox" onchange="zavrsiZadatak(${zadatak.id})" ${zavrsen_chk} />`;
    let dugme_brisanje = `<button class="btn btn-sm btn-danger " onclick="ukloniZadatak(${zadatak.id})" ><i class="fa fa-times"></i></button>`;
    let dugme_izmjena = `<button class="btn btn-sm btn-primary " onclick="izmijeniZadatak(${zadatak.id})" ><i class="fa fa-edit"></i></button>`;
    tabela.push(
      `<tr id="red_${zadatak.id}" class="${klasa_zavrsen}">
        <td>${zadatak.id}</td>
        <td>${zadatak.tekst}</td>
        <td>${zadatak.opis}</td>
        <td>${chk_box}</td>
        <td>${dugme_brisanje}</td>
        <td>${dugme_izmjena}</td>
      </tr>`
    );
  });
  tabela_body.html(tabela.join(""));

  let broj = document.getElementById("broj_zadataka");
  broj.textContent = `Broj zadataka: ${zadaci.length}`;
}

function generisiNoviID() {
  let max = 0;
  for (let i = 0; i < zadaci.length; i++) {
    if (zadaci[i].id > max) max = zadaci[i].id;
  }
  return Number(max) + 1;
}

function zavrsiZadatak(id) {
  let zadatak = zadaci.find((zadatak) => zadatak.id == id);
  zadatak.zavrsen = !zadatak.zavrsen;
  $.ajax({
    type: "POST",
    url: api_route + "/complete_task.php",
    data: { id },
    success: () => {
      $("#red_" + id).toggleClass("zavrsen");
    },
  });
  // prikaziZadatke();
}

function ukloniZadatak(id) {
  if (confirm("Da li ste sigurni?")) {
    zadaci = zadaci.filter((zadatak) => zadatak.id != id);
    prikaziZadatke();
    $.ajax({
      type: "POST",
      url: api_route + "/delete_task.php",
      data: { id },
      success: (result) => {
        if (result == "OK") {
          prikaziZadatke();
          addAlert("Uspjesno brisanje!", "success");
        } else {
          console.log(result);
          addAlert("Greska pri brisanju!", "danger");
        }
      },
    });
  }
}

function izmijeniZadatak(id) {
  let zadatak = zadaci.find((zadatak) => zadatak.id == id);
  document.getElementById("izmjena_tekst").value = zadatak.tekst;
  document.getElementById("izmjena_opis").value = zadatak.opis;
  document.getElementById("index_izmjena").value = id;
  $("#modal_izmjena").modal("show");
}

function isprazniPolja(tip) {
  if (tip == "izmjena") {
    document.getElementById("izmjena_tekst").value = "";
    document.getElementById("izmjena_opis").value = "";
    document.getElementById("index_izmjena").value = -1;
  } else if (tip == "dodavanje") {
    document.getElementById("novi_zadatak_tekst").value = "";
    document.getElementById("novi_zadatak_opis").value = "";
  } else if (tip == "pretraga") {
    document.getElementById("pretraga_tekst").value = "";
    document.getElementById("pretraga_opis").value = "";
    document.getElementById("pretraga_zavrsen").value = "";
  }
}

citajZadatke().then(() => {
  isprazniPolja("pretraga");
  prikaziZadatke();
});

// dodavanje event listener-a
$("#dodaj_novi_forma").submit((e) => {
  e.preventDefault();
  let novi_tekst = document.getElementById("novi_zadatak_tekst").value;
  let novi_opis = document.getElementById("novi_zadatak_opis").value;
  let novi_zadatak = {
    id: generisiNoviID(),
    tekst: novi_tekst,
    opis: novi_opis,
    zavrsen: false,
  };
  zadaci.push(novi_zadatak);

  $.ajax({
    type: "POST",
    url: api_route + "/add_task.php",
    data: novi_zadatak,
    success: (result) => {
      if (result == "OK") {
        prikaziZadatke();
        $("#modal_dodavanje").modal("hide");
        isprazniPolja("dodavanje");
        addAlert("Uspjesno dodavanje!", "success");
      } else {
        console.log(result);
        addAlert("Greska pri dodavanju!", "danger");
      }
    },
  });
});

$("#izmjena_zadatka_forma").submit((e) => {
  e.preventDefault();
  let id = document.getElementById("index_izmjena").value;
  let zadatak = zadaci.find((zadatak) => zadatak.id == id);
  zadatak.tekst = document.getElementById("izmjena_tekst").value;
  zadatak.opis = document.getElementById("izmjena_opis").value;

  $.ajax({
    type: "POST",
    url: api_route + "/edit_task.php",
    data: zadatak,
    success: (result) => {
      if (result == "OK") {
        prikaziZadatke();
        $("#modal_izmjena").modal("hide");
        isprazniPolja("izmjena");
        addAlert("Uspjesna izmjena!", "success");
      } else {
        console.log(result);
        addAlert("Greska pri izmjeni!", "danger");
      }
    },
  });
});

$("#pretraga_forma").submit((e) => {
  e.preventDefault();

  let tekst = $("#pretraga_tekst").val();
  let opis = $("#pretraga_opis").val();
  let zavrsen = $("#pretraga_zavrsen").val();
  console.log(zavrsen);

  $.post({
    type: "POST",
    url: api_route + "/get_tasks.php",
    data: { pretraga: "", tekst: tekst, opis: opis, zavrsen: zavrsen },
    success: (result) => {
      zadaci = JSON.parse(result);
      prikaziZadatke();
    },
  });
});

function addAlert(text, status) {
  clearTimeout(timeout);
  let alert = cleanAlert();
  alert.textContent = text;
  alert.classList.add("alert");
  alert.classList.add(`alert-${status}`);
  alert.classList.add("my-0");
  timeout = setTimeout(cleanAlert, 3000);
}

function cleanAlert() {
  let alert = document.getElementById("alert");
  alert.className = "";
  alert.textContent = "";
  return alert;
}
