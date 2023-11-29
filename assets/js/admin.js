/** @format */

(() => {
  //ヘッダーナビゲーション設定
  const $menuBtn = document.querySelector("#menu-btn");
  const $header = document.querySelector(".header");

  $menuBtn.addEventListener("click", () => {
    $header.classList.toggle("active");
    $menuBtn.classList.toggle("fa-times");
  });

  window.addEventListener("scroll", () => {
    $header.classList.remove("active");
    $menuBtn.classList.remove("fa-times");
  });

  //show-posts sectionのpost-contentの設定
  const $content = document.querySelectorAll(".post-content");
  $content.forEach((text) => {
    if(text.innerHTML.length > 100){
      text.innerHTML = text.innerHTML.slice(0, 100)
    }
  });
})();
