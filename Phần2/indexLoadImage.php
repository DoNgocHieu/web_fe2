<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Do Ngoc Hieu</title>
  <link rel="stylesheet" href="public/bootstrap/bootstrap-5.3.0-dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="public/assets/font/bootstrap-icons.min.css" />
  <style>
    * {
      box-sizing: border-box;
      padding: 0;
      margin: 0;
    }

    .parent {
      width: 100%;
      height: 100vh;
      position: fixed;
      perspective: 500px;
    }

    .background {
      width: 100%;
      height: 100%;
      background: url(https://hoanghamobile.com/tin-tuc/wp-content/webp-express/webp-images/uploads/2023/09/hinh-nen-vu-tru-30.jpg.webp);
      background-repeat: no-repeat;
      background-size: cover;
      position: fixed;
      scale: 1.2;
      z-index: -1;
      transition: all 0.5s linear;
    }

    .box {
      width: 100%;
      height: 100%;
      transform-style: preserve-3d;
      transition: all 0.5s linear;
    }

    .card {
      position: absolute;
      width: 160px;
      transition: all 1s;
      box-shadow: 0 0 20px black;
      background: transparent;
    }

    .card:hover {
      filter: brightness(200%) !important;
      transform: translateZ(100px) !important;
      box-shadow: 0 0 20px rgb(237, 32, 168) !important;
    }

    .card-content {
      background-color: white;
      width: 160px;
      height: 160px;
    }

    .card-footer {
      width: 160px;
      height: 50px;
      background-color: rgba(0, 0, 0, 0.2);
    }
  </style>
</head>

<body>
  <div class="parent">
    <button class="btn btn-primary" onclick="loadMore(photos)">Load more</button>
    <div class="background"></div>
    <div class="box">
    </div>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
  <script>
    const parent = document.querySelector(".parent");
    const background = document.querySelector(".background");
    const box = document.querySelector(".box");

    const centerWidth = window.innerWidth / 2;
    const centerHeight = window.innerHeight / 2;

    parent.addEventListener("mousemove", (event) => {
      const mouseX = ((event.clientX - centerWidth) / centerWidth) * -5;
      const mouseY = ((event.clientY - centerHeight) / centerHeight) * 5;
      const mouseBoxX = ((event.clientX - centerWidth) / centerWidth) * 15;
      const mouseBoxY = ((event.clientY - centerHeight) / centerHeight) * -15;
      background.style.transform = `rotateY(${mouseX}deg) rotateX(${mouseY}deg)`;
      box.style.transform = `rotateY(${mouseBoxX}deg) rotateX(${mouseBoxY}deg)`;
    });

    //Load more
    let photos = [];
    async function loadMore(arrPhotoId) {
      let btnLoadMore = document.querySelector('.btn');
      btnLoadMore.textContent = "Loading ....";
      const url = "app/api/loadImage.php";
      const data = {
        photoId: arrPhotoId
      };
      const response = await fetch(url, {
        method: "POST",
        body: JSON.stringify(data)
      });
      const result = await response.json();

      result.slice(0, 4).forEach(element => {
        // Tạo phần tử div cho thẻ card
        const div = document.createElement('div');
        const randomNumber = (Math.floor(Math.random() * 11) / 10).toFixed(1);

        div.innerHTML = ` 
            <div class="card">
                 <img src="${element.source}" class="card-content card-img-top img img-fluid w-100" alt="">
                <div class="card-footer text-light">
                <p class="text-light">
                        <i class="bi bi-heart-fill"></i>
                        <span class="numberLike">${element.likes}</span>
                    </p>
                    <p class="text-light">
                        <i class="bi bi-eye-fill"></i>
                        <span class="numberView">${element.views}</span>
                    </p>
                </div>
            </div>`;
        let card = div.firstElementChild;

        let imageCard = card.querySelector('.card-img-top');
        let numberView = card.querySelector('.numberView');
        let numberLike = card.querySelector('.numberLike');
        let iconLike = card.querySelector('.bi-heart-fill');
        imageCard.setAttribute('data-bs-toggle', "modal");
        imageCard.setAttribute('data-bs-target', "#exampleModal");
        imageCard.setAttribute('data-image', element.source);
        imageCard.setAttribute('data-desc', element.description);
        //Random vi tri card
        card.style.position = 'absolute';
        card.style.left = Math.floor(Math.random() * (60 - 10 + 2) + 10) + "%";
        card.style.top = Math.floor(Math.random() * (80 - 10 + 2) + 10) + "%";
        card.style.transform = "translateZ(" + randomNumber * -260 + "px)";
        card.style.filter = `brightness(${100 - (randomNumber * 100)}%)`;

        //Them card vao box
        box.appendChild(card);
        //Them id vao mang
        arrPhotoId.push(element.id);

        imageCard.addEventListener('click', function () {
          //Update modal
          document.querySelector('.modal-body').innerHTML = `<img src="${imageCard.dataset.image}" class="card-img-top img img-fluid w-100" alt="">`;
          //Update view
          views(element.id, numberView);
        });

        iconLike.addEventListener('click', function () {
          likes(element.id, numberLike);
        });
      });

      //Be hon 3 chung to het roi
      if (result.length < 3) {
        btnLoadMore.remove();
      } else {
        btnLoadMore.textContent = "Load more";
      }
    }
    loadMore(photos);

    //Ham tang view
    async function views(id, target) {
      const url = "app/api/viewImage.php";
      const data = {
        photoId: id
      };
      const response = await fetch(url, {
        method: "POST",
        body: JSON.stringify(data)
      });
      const result = await response.json();
      target.textContent = result.views;
    }
    //Ham tang like
    async function likes(id, target) {
      const url = "app/api/likeImage.php";
      const data = {
        photoId: id
      };
      const response = await fetch(url, {
        method: "POST",
        body: JSON.stringify(data)
      });
      const result = await response.json();
      target.textContent = result.likes;
    }
  </script>
  <script src="./public/bootstrap/bootstrap-5.3.0-dist/js/bootstrap.min.js"></script>

</body>

</html>
