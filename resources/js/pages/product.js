const page = 6;
let currentPage = 1;

const getData = async (url) => {
    let data = await fetch(
        `http://localhost/the-leafy-liberties/data/${url}`
    ).then((response) => response.json());
    return data;
};
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

const handlePageNumber = async (number, products) => {
    if (!products.length) return renderProducts(products);
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
const handlePagination = async (products) => {
    await renderPageNumber(products);
};

const renderPageNumber = async (products) => {
    // console.log(products.length)
    if (products.length == 0)
        document.getElementById("pagination").innerHTML = "";
    else {
        // console.log("Pagination")
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
    }
};

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
              <a href="/the-leafy-liberties/product?id=${product.id}">
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
                  <p class="p-2 hover:bg-primary hover:text-white rounded-sm font-semibold select-option-text cursor-pointer transition-all">Add to cart</p>
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

const filterByPrice = (products, priceRange) => {
    let filteredProducts = [];

    for (let i = 0; i < products.length; i++) {
        let product = products[i];
        if (priceRange === "lessThan10" && product.price < 10) {
            filteredProducts.push(product);
        } else if (
            priceRange === "between11And30" &&
            product.price >= 11 &&
            product.price <= 30
        ) {
            filteredProducts.push(product);
        } else if (priceRange === "greaterThan30" && product.price > 30) {
            filteredProducts.push(product);
        }
    }

    return filteredProducts;
};

const resetOption = async (event) => {
    event.preventDefault();

    document.getElementById("less-than-10").checked = false;
    document.getElementById("11-to-30").checked = false;
    document.getElementById("greater-than-30").checked = false;
    document.getElementById("high-to-low").checked = false;
    document.getElementById("low-to-high").checked = false;
};

const filterProducts = async (event) => {
    event.preventDefault();

    let products = await categoriesFilter();

    const lessThan10 = document.getElementById("less-than-10").checked;
    const between11And30 = document.getElementById("11-to-30").checked;
    const greaterThan30 = document.getElementById("greater-than-30").checked;
    const highToLow = document.getElementById("high-to-low").checked;
    const lowToHigh = document.getElementById("low-to-high").checked;

    let filteredProducts = products;

    if (highToLow) {
        products.sort((a, b) => b.price - a.price);
    } else if (lowToHigh) {
        products.sort((a, b) => a.price - b.price);
    }

    if (lessThan10) {
        filteredProducts = filterByPrice(products, "lessThan10");
    } else if (between11And30) {
        filteredProducts = filterByPrice(products, "between11And30");
    } else if (greaterThan30) {
        filteredProducts = filterByPrice(products, "greaterThan30");
    }

    await handlePagination(filteredProducts);
    handlePageNumber(null, filteredProducts);
};

const categoriesFilter = async () => {
    var url = window.location.href;
    var params = new URLSearchParams(new URL(url).search);
    var categoryId = params.get("category");
    let categoriesFilter = [];

    let products = await getData("getProducts");
    let productCategories = await getData("getProductCategories");
    if (categoryId == "All" || categoryId == "" || categoryId == null)
        return products;
    for (let i = 0; i < productCategories.length; i++) {
        if (productCategories[i].category_id == categoryId) {
            for (let j = 0; j < products.length; j++) {
                if (productCategories[i].product_id == products[j].id) {
                    categoriesFilter.push(products[j]);
                }
            }
        }
    }
    return categoriesFilter;
};

window.onload = async function () {
    const products = await categoriesFilter();
    await handlePagination(products);
    handlePageNumber(null, products);
};
