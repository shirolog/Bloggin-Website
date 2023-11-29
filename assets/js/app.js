/** @format */

(() => {
  //ヘッダーナビゲーション設定
  const $menuBtn = document.querySelector("#menu-btn");
  const $navBar = document.querySelector(".navbar");
  const $userBtn = document.querySelector("#user-btn");
  const $profile = document.querySelector(".profile");
  const $searchBtn = document.querySelector("#search-btn");
  const $searchForm = document.querySelector(".header .flex .search-form");

  $menuBtn.addEventListener("click", () => {
    $navBar.classList.toggle("active");
    $profile.classList.remove("active");
    $searchForm.classList.remove("active");
  });

  $userBtn.addEventListener("click", () => {
    $profile.classList.toggle("active");
    $navBar.classList.remove("active");
    $searchForm.classList.remove("active");
  });

  $searchBtn.addEventListener("click", () => {
    $searchForm.classList.toggle("active");
    $profile.classList.remove("active");
    $navBar.classList.remove("active");
  });

  window.addEventListener("scroll", () => {
    $navBar.classList.remove("active");
    $profile.classList.remove("active");
    $searchForm.classList.remove("active");
  });

  //posts-grid section contentの字数制限設定

  const $contents = document.querySelectorAll(
    ".posts-grid .box-container .box .content"
  );

  $contents.forEach((content) => {
    if(content.innerHTML.length > 150){
      content.innerHTML = content.innerHTML.slice(0, 150);
    }
  });


})();
