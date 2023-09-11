import { handlePageNumber } from "./handle-page-number.js";

const page = 6;
let currentPage = 1;

const getDataByPagination = (number, products) => {
  currentPage = number ? number : 1;
  let perProducts = [];
  perProducts = products.slice(
    (currentPage - 1) * page,
    (currentPage - 1) * page + page
  );
  return perProducts;
};
const handleClick = (i, products) => {
  const totalPage = Math.ceil(products.length / page);

  if (i == 0)
    return () => {
      currentPage--;
      handlePageNumber(currentPage, products);
    };
  else if (i == totalPage + 1)
    return () => {
      currentPage++;
      handlePageNumber(currentPage, products);
    };
  else
    return () => {
      handlePageNumber(i, products);
    };
};

export { getDataByPagination, page, currentPage, handleClick };
