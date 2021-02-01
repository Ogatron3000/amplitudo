document.querySelector("form").onsubmit = (e) => {
  e.preventDefault();

  // isprazni DOM
  let error = document.querySelector("#error");
  error.innerHTML = "";
  let poster = document.querySelector("img");
  poster.src = "";
  let table = document.querySelector("tbody");
  table.innerHTML = "";
  let loader = document.querySelector("#loader");
  loader.classList.remove("d-none");

  // izvuci podatke iz forme
  let title = document.querySelector("#title").value;
  if (title.includes(" ")) {
    title = title.split(" ").join("+");
  }
  let type = document.querySelector("#type").value;
  let year = document.querySelector("#year").value;

  // povezi se sa API-jem i dodaj podatke u DOM
  fetch(
    `http://www.omdbapi.com/?apikey=7a53df7a&t=${title}&type=${type}&y=${year}`
  )
    .then((res) => res.json())
    .then(
      ({
        Title,
        Year,
        Released,
        Runtime,
        Director,
        Writer,
        Actors,
        Plot,
        totalSeasons,
        Ratings,
        Poster,
        Error,
      }) => {
        loader.classList.add("d-none");
        if (Error) {
          error.innerHTML =
            '<i id="sad" class="fas fa-frown d-block"></i><span>No movie found.</span>';
        } else {
          let tags = {
            Title,
            Year,
            Released,
            Runtime,
            Director,
            Writer,
            Actors,
            Plot,
          };
          if (totalSeasons) {
            tags["Seasons"] = totalSeasons;
          }
          tags["Ratings"] = Ratings;
          if (Poster !== "N/A") {
            poster.src = Poster;
          } else {
            poster.src = "./images/poster.png";
          }
          for (let tag in tags) {
            make(tag, tags[tag]);
          }
        }
      }
    );
};

// dodaje tag i tekst u DOM
function make(tag, text) {
  let tbody = document.querySelector("tbody");

  if (tag === "Ratings") {
    let ratingsTable = text
      .map(
        (rating) =>
          `<tr><td></td><td>${
            rating.Source
          }:</td><td class='hey'><div class="progress"><div class="progress-bar bg-dark" style="width: ${percent(
            rating.Value
          )}">${rating.Value}</div></div></td></tr>`
      )
      .join("");
    tbody.innerHTML += `<tr><td>${tag}:</td><td>${ratingsTable}</td></tr>`;
  } else {
    tbody.innerHTML += `<tr><td>${tag}:</td><td colspan='2'>${text}</td></tr>`;
  }
}

// konvertuje rejting u procente
function percent(value) {
  if (value.includes(".")) {
    value = value[0] + value[2] + "%";
  } else if (!value.includes("%")) {
    value = value.split("/")[0] + "%";
  }

  return value;
}

// JQUERY AJAX metoda

// $.ajax({
//   type: "GET",
//   url: `http://www.omdbapi.com/?apikey=7a53df7a&t=${title}&type=${type}&y=${year}`,
//   success: (res) => {
//     let {
//       Title,
//       Year,
//       Released,
//       Runtime,
//       Director,
//       Writer,
//       Actors,
//       Plot,
//       totalSeasons,
//       Ratings,
//       Poster,
//       Error,
//     } = JSON.parse(res);
//     ...ostatak je isti kao gore...
//   },
// });
