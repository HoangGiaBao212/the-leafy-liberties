$(document).ready(function () {
  $(".bestselling-products").slick({
    slidesToShow: 5,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 5000,
    infinite: true,
    prevArrow: `<button type="button" class="slick-prev pull-left absolute top-[45%] z-10 ">
      <i class="fa-solid fa-chevron-left bg-gray-300 p-3 w-10 h-10 text-white rounded-full hover:bg-primary-600 transition-all"></i>
      </button>`,
    nextArrow: `<button type="button" class="slick-prev pull-right absolute top-[45%] z-10 right-0 ">
      <i class="fa-solid fa-chevron-right bg-gray-300 p-3 w-10 h-10 text-white rounded-full hover:bg-primary-600 transition-all"></i>
      </button>`,
    pauseOnHover: true,
    responsive: [
      {
        breakpoint: 1200,
        settings: {
          slidesToShow: 4,
          slidesToScroll: 4,
          infinite: true,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 992,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 3,
          infinite: true,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2,
          infinite: true,
          slidesToScroll: 1,
        },
      },
    ],
  });
  abc();
});

function abc() {
  $(".author_slide").slick({
    slidesToShow: 5,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 4000,
    infinite: true,
    prevArrow: `<button type="button" class="slick-prev pull-left absolute top-[30%] z-10 ">
    <i class="fa-solid fa-chevron-left bg-gray-300 p-3 w-10 h-10 text-white rounded-full hover:bg-primary-600 transition-all"></i>
    </button>`,
    nextArrow: `<button type="button" class="slick-prev pull-right absolute top-[30%] z-10 right-0 ">
    <i class="fa-solid fa-chevron-right bg-gray-300 p-3 w-10 h-10 text-white rounded-full hover:bg-primary-600 transition-all"></i>
    </button>`,
    pauseOnHover: true,
    responsive: [
      {
        breakpoint: 1200,
        settings: {
          slidesToShow: 4,
          slidesToScroll: 4,
          infinite: true,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 992,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 3,
          infinite: true,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2,
          infinite: true,
          slidesToScroll: 1,
        },
      },
    ],
  });
  $(".category_slide").slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 4500,
    infinite: true,
    prevArrow: `<button type="button" class="slick-prev pull-left absolute top-[45%] z-10 ">
    <i class="fa-solid fa-chevron-left bg-gray-300 p-3 w-10 h-10 text-white rounded-full hover:bg-primary-600 transition-all"></i>
    </button>`,
    nextArrow: `<button type="button" class="slick-prev pull-right absolute top-[45%] z-10 right-0 ">
    <i class="fa-solid fa-chevron-right bg-gray-300 p-3 w-10 h-10 text-white rounded-full hover:bg-primary-600 transition-all"></i>
    </button>`,
    pauseOnHover: true,
    responsive: [
      {
        breakpoint: 1200,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 3,
          infinite: true,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 992,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2,
          infinite: true,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          infinite: true,
          slidesToScroll: 1,
        },
      },
    ],
  });
}
