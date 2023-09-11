const getData = async (url) => {
  let data = await fetch(
    `http://localhost/the-leafy-liberties/data/${url}`
  ).then((response) => response.json());
  return data;
};

export { getData };
