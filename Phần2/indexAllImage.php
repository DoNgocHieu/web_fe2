<!-- All Image -->
<?php
require_once 'app/models/PhotoModel.php';
require_once "config/database.php";
$photoModel = new PhotoModel();
$photos = $photoModel->getAllPhotos();
?>
<!-- All Image -->
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

    .list-group-item {
      background: white;
      padding: 10px;
      border: 1px solid #ddd;
      margin-bottom: 5px;
      cursor: pointer;
      transition: all 0.3s;
    }

    .list-group-item:hover {
      background: #f0f0f0;
    }

    .result_search {
      position: fixed;
      left: 30%;
      top: 10%;
      width: 500px;
      max-height: 300px;
      overflow-y: auto;
      background: white;
      border: 1px solid #ddd;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
      z-index: 1000;
    }

    .searchInput {
      width: 500px;
      position: fixed;
      left: 30%;
      top: 2%;
    }
  </style>
</head>

<body>
  <div class="parent">
    <!-- Phần Search -->
    <input type="text" placeholder="Search..." class="searchInput form-control">
    <ul class="result_search list-group"></ul>
    <!-- Phần Search -->
    <button class="btn btn-primary" onclick="">Thêm Image</button>
    <div class="background"></div>
    <div class="box"></div>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Detail Image</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <img id="modalImage" src="" alt="Image" style="width:100%; height:auto">
          <p id="modalTitle"></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Xu lý script -->
  <script src="public/bootstrap/bootstrap-5.3.0-dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const parent = document.querySelector(".parent");
    const background = document.querySelector(".background");
    const box = document.querySelector(".box");
    
    
    // <!-- All Image -->
    const photos = <?php echo json_encode($photos); ?>;

    photos.forEach(photo => {
      const randomNumber = (Math.floor(Math.random() * 11) / 10).toFixed(1);
      const card = document.createElement("div");
      card.className = "card";
      card.innerHTML = `
        <div class="card-content">
          <img src="${photo.source}" data-title="${photo.title}" data-bs-toggle="modal" data-bs-target="#myModal" alt="${photo.title}" style="width:100%; height:100%">
        </div>
        <div class="card-footer">
          <p class="text-light">
            <i class="bi bi-heart-fill" data-id="${photo.id}"></i>
             <span class="numberLike" id="like-${photo.id}">${photo.likes}</span>
          </p>
        </div>`;
            // <span class="numberLike">${photo.likes}</span>
      card.style.top = Math.floor(Math.random() * (60 - 10 + 2) + 10) + "%";
      card.style.left = Math.floor(Math.random() * (80 - 10 + 2) + 10) + "%";
      card.style.transform = "translateZ(" + randomNumber * -260 + "px)";
      card.style.filter = `brightness(${100 - (randomNumber * 100)}%)`;
      box.appendChild(card);
    });

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
    // <!-- All Image -->

    // Search
    const result_search = document.querySelector(".result_search");
    const searchInput = document.querySelector(".searchInput");
    
    searchInput.addEventListener("input", async (e) => {
      const keySearch = e.target.value;

      if (keySearch === "") {
        result_search.innerHTML = "";
        result_search.style.display = "none";
        return;
      }

      const url = "app/api/searchImage.php";
      const data = {
        keySearch: keySearch
      };
      const response = await fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
      });

      const result = await response.json();
      result_search.innerHTML = "";
      result_search.style.display = "block";

      result.forEach(photo => {
        const li = document.createElement("li");
        li.innerHTML = `<a href="#" data-src="${photo.source}" data-title="${photo.title}" data-bs-toggle="modal" data-bs-target="#myModal" class="list-group-item">${photo.title}</a>`;
        result_search.appendChild(li);
      });

      document.querySelectorAll('.list-group-item').forEach(item => {
        item.addEventListener('click', function () {
          const src = this.getAttribute('data-src');
          const title = this.getAttribute('data-title');
          modalImage.setAttribute('src', src);
          modalTitle.textContent = title;
        });
      });
    });
    
    // Like Image
    document.querySelectorAll('.bi-heart-fill').forEach(likeIcon => {
      likeIcon.addEventListener('click', async function () {
        const id = this.dataset.id;
        const numberLike = document.getElementById(`like-${id}`);
        numberLike.style.color = "red";
        likeIcon.style.color = "red";
        await likes(id, numberLike);
      });
    });

    async function likes(id, target) {
      const url = "app/api/likeImage.php";
      const data = {
        photoId: id
      };
      const response = await fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
      });
      const result = await response.json();
      target.textContent = result.likes;
    }

    // Show image in modal
    const modalImage = document.getElementById("modalImage");
    const modalTitle = document.getElementById("modalTitle");
    document.querySelectorAll('.card-content img').forEach(img => {
      img.addEventListener('click', function () {
        const src = this.getAttribute('src');
        const title = this.getAttribute('data-title');
        modalImage.setAttribute('src', src);
        modalTitle.textContent = title;
      });
    });
  </script>
</body>

</html>
