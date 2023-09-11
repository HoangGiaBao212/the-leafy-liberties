import { page } from "../Pagination/get-data-by-pagination.js";
import { handleClick } from "../Pagination/get-data-by-pagination.js";

const renderPageNumber = async (products) => {
  const totalPage = Math.ceil(products.length / page);
  document.getElementById("pagination").innerHTML = "";
  for (var i = 0; i < totalPage + 2; i++) {
    const li = document.createElement("li");
    if (i == 0) {
      li.innerHTML = "Prev";
      li.onclick = handleClick(i, products);
      li.classList.add(
        "bg-primary",
        "p-3",
        "rounded-full",
        "text-white",
        "w-13",
        "h-12",
        "cursor-pointer",
        "hover:bg-primary-500",
        "transition-all",
        `page${i}`
      );
    } else if (i == totalPage + 1) {
      li.innerHTML = "Next";
      li.onclick = handleClick(i, products);
      li.classList.add(
        "bg-primary",
        "p-3",
        "rounded-full",
        "text-white",
        "w-13",
        "h-12",
        "cursor-pointer",
        "hover:bg-primary-500",
        "transition-all",
        `page${i}`
      );
    } else {
      li.innerHTML = i;
      li.onclick = handleClick(i, products);
      li.classList.add(
        "bg-primary",
        "p-3",
        "rounded-full",
        "text-white",
        "w-12",
        "h-12",
        "cursor-pointer",
        "hover:bg-primary-500",
        "transition-all",
        `page${i}`
      );
    }
    document.getElementById("pagination").appendChild(li);
  }
};

export { renderPageNumber };
