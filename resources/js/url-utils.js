export function parseUrlParameters(paramsString) {
  const params = new URLSearchParams(paramsString);
  const result = {};

  for (const [key, value] of params.entries()) {
    const keys = key.split(/\[(.*?)\]/).filter((k) => k !== "");
    let obj = result;

    for (let i = 0; i < keys.length - 1; i++) {
      const currentKey = keys[i];
      const nextKey = keys[i + 1];

      if (typeof obj[currentKey] === "undefined") {
        obj[currentKey] = !isNaN(parseInt(nextKey)) ? [] : {};
      }

      obj = obj[currentKey];
    }

    const lastKey = keys[keys.length - 1];
    if (lastKey === "") {
      obj.push(value);
    } else {
      obj[lastKey] = value;
    }
  }

  return result;
}
