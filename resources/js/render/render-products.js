import { getData } from "../getDataFormServer/get-data.js";

const renderProducts = async (products) => {
  const productContainer = document.getElementById("product-list");
  productContainer.innerHTML = "";

  let authors = await getData("getAuthors");

  products.forEach((product) => {
    const productElement = document.createElement("div");
    productElement.classList.add("product");
    const author = authors.find((author) => author.id === product.author_id);
    productContainer.innerHTML += `
        <div class="flex flex-col items-center justify-center w-full px-[22px] box-border pt-5 product-info group border-solid border hover:border-gray-500 transition-all hover:shadow-xl">
            <div class="object-cover h-full p-2 w-60">
            <a href="/the-leafy-liberties/product?id=${product.id}">
            <img src="${product.image}" alt="" class="object-cover w-full h-full" />
            </a>
            </div>
            <div class="flex flex-col items-start justify-center w-full p-1 text-lg font-medium transition-all bg-white product-body group-hover:-translate-y-16">
            <div class="product-name">
                <a href="/">
            ${product.name}
                </a>
            </div>
            <div class="text-sm text-gray-500 product-author">
            ${author.name}
            </div>
            <div class="p-0 font-semibold product-price text-primary-900">
            ${product.price}
            </div>
            <div class="flex items-center justify-between w-full transition-all translate-y-0 opacity-0 heart-option group-hover:opacity-100">
                <p class="font-semibold select-option-text">Add to cart</p>
                <i class="p-2 transition-all rounded-full cursor-pointer fa-regular fa-heart hover:bg-red-400 hover:text-white"></i>
            </div>
            </div>
        </div>
        `;
  });
  if (productContainer.innerHTML === "") {
    productContainer.innerHTML += `<h1 class="text-sm sm:text-4xl text-primary-600 w-[1100px] text-center">Không có sản phẩm nào</h1>`;
  }
};

export { renderProducts };
