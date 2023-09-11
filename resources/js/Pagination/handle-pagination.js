import { renderPageNumber } from "../render/render-page-number.js";
import { renderProducts } from "../render/render-products.js";
import { getDataByPagination } from "./get-data-by-pagination.js";

const handlePagination = async (products) => {
  await renderPageNumber(products);
  const perProducts = getDataByPagination(null, products);
  renderProducts(perProducts);
};

export { handlePagination };
