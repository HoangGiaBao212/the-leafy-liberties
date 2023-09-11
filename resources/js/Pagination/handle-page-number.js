import { renderProducts } from "../render/render-products.js";
import { getDataByPagination } from "./get-data-by-pagination.js";
import { currentPage } from "./get-data-by-pagination.js";

const handlePageNumber = async (number, products) => {
  const pagination = document.querySelector("ul");
  const liElements = pagination.querySelectorAll("li");
  const perProducts = getDataByPagination(number, products);

  for (let i = 0; i < liElements.length; i++) {
    if (i == currentPage) liElements[i].classList.add("bg-red-800");
    else liElements[i].classList.remove("bg-red-800");
    if (
      (currentPage == 1 && i == 0) ||
      (currentPage + 2 == liElements.length && i + 1 == liElements.length)
    )
      liElements[i].classList.add("hidden");
    else liElements[i].classList.remove("hidden");
  }
  renderProducts(perProducts);
};

export { handlePageNumber };
