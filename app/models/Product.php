<?php

namespace App\Models;

use App\Models\ProductCategory;
use Core\Model;
use App\Models\Author;
use App\Models\ProductTag;
use App\Models\Publisher;
use App\Models\Tag;
use Core\Traits\SoftDeletes;

class Product extends Model
{
  use SoftDeletes;
  protected $table = "products";

  protected $fillable = [
    "id",
    "isbn",
    "name",
    "author_id",
    "publisher_id",
    "price",
    "description",
    "image",
    "quantity",
    "deleted_at",
  ];
  public function author()
  {
    return Author::find($this->author_id);
  }

  public function publisher()
  {
    return Publisher::find($this->publisher_id);
  }
  public function tags()
  {
    $productTags = ProductTag::findAll(["product_id" => $this->id]);
    $tags = [];
    foreach ($productTags as $productTag) {
      $tags[] = Tag::find($productTag->tag_id);
    }
    return $tags;
  }
  public function addTag($tag)
  {
    ProductTag::create([
      "product_id" => $this->id,
      "tag_id" => $tag->id,
    ]);
  }

  public function removeTag($tag)
  {
    $productTags = ProductTag::findAll(["product_id" => $this->id]);
    $productTag = null;
    foreach ($productTags as $productTag) {
      if ($productTag->tag_id == $tag->id) {
        break;
      }
    }
    $productTag->delete();
  }

  public function hasTag($tag)
  {
    $productTags = ProductTag::findAll(["product_id" => $this->id]);
    foreach ($productTags as $productTag) {
      if ($productTag->tag_id == $tag->id) {
        return true;
      }
    }
    return false;
  }
  public function categories()
  {
    $productCategories = ProductCategory::findAll(["product_id" => $this->id]);
    $categories = [];
    foreach ($productCategories as $productCategory) {
      $categories[] = Category::find($productCategory->category_id);
    }
    return $categories;
  }

  public function removeAllCategories()
  {
    $productCategories = ProductCategory::findAll(["product_id" => $this->id]);
    foreach ($productCategories as $productCategory) {
      $productCategory->delete();
    }
  }

  public function removeAllTags()
  {
    $productTags = ProductTag::findAll(["product_id" => $this->id]);
    foreach ($productTags as $productTag) {
      $productTag->delete();
    }
  }

  public function addCategory($category)
  {
    ProductCategory::create([
      "product_id" => $this->id,
      "category_id" => $category->id,
    ]);
  }

  public function removeCategory($category)
  {
    $productCategories = ProductCategory::findAll(["product_id" => $this->id]);
    $productCategory = null;
    foreach ($productCategories as $productCategory) {
      if ($productCategory->category_id == $category->id) {
        break;
      }
    }
    $productCategory->delete();
  }

  public function hasCategory($category)
  {
    $productCategories = ProductCategory::findAll(["product_id" => $this->id]);
    foreach ($productCategories as $productCategory) {
      if ($productCategory->category_id == $category->id) {
        return true;
      }
    }
    return false;
  }
  public function getProduct()
  {
    return Product::find($this->id);
  }
  public static function filterAdvanced($filter)
  {
    $products = Product::all();
    if (!empty($filter['order-by'])) {
      $orderBy = $filter['order-by'];
      $orderDirection = $filter['order-direction'] ?? 'asc';
      usort($products, function ($a, $b) use ($orderBy, $orderDirection) {
        if ($orderDirection == 'asc') {
          if ($orderBy == 'price' || $orderBy == 'quantity' || $orderBy == 'id') {
            return floatval($a->$orderBy) - floatval($b->$orderBy);
          } else {
            return strcmp($a->$orderBy, $b->$orderBy);
          }
        } else {
          if ($orderBy == 'price' || $orderBy == 'quantity' || $orderBy == 'id') {
            return floatval($b->$orderBy) - floatval($a->$orderBy);
          } else {
            return strcmp($b->$orderBy, $a->$orderBy);
          }
        }
      });
    }

    // Filter by keywords
    if (!empty($filter['keywords'])) {
      $filteredProducts = [];
      foreach ($products as $product) {
        if (strpos(strtolower($product->name), strtolower($filter['keywords'])) !== false) {
          $filteredProducts[] = $product;
        }
      }
      $products = $filteredProducts;
    }

    // Filter by categories
    if (!empty($filter['categories'])) {
      $categories = $filter['categories'];
      $filteredProducts = [];
      foreach ($products as $product) {
        foreach ($product->categories() as $category) {
          if (in_array($category->id, $categories)) {
            $filteredProducts[] = $product;
            break;
          }
        }
      }
      $products = $filteredProducts;
    }

    // Filter by tags
    if (!empty($filter['tags'])) {
      $tags = $filter['tags'];
      $filteredProducts = [];
      foreach ($products as $product) {
        foreach ($product->tags() as $tag) {
          if (in_array($tag->id, $tags)) {
            $filteredProducts[] = $product;
            break;
          }
        }
      }
      $products = $filteredProducts;
    }

    // Filter by author
    if (!empty($filter['author'])) {
      $authorId = $filter['author'];
      $filteredProducts = [];
      foreach ($products as $product) {
        if ($product->author_id == $authorId) {
          $filteredProducts[] = $product;
        }
      }
      $products = $filteredProducts;
    }

    // Filter by price range
    if (!empty($filter['price']['min'])) {
      $minPrice = $filter['price']['min'];
      $filteredProducts = [];
      foreach ($products as $product) {
        if (floatval($product->price) >= floatval($minPrice)) {
          $filteredProducts[] = $product;
        }
      }
      $products = $filteredProducts;
    }
    if (!empty($filter['price']['max'])) {
      $maxPrice = $filter['price']['max'];
      $filteredProducts = [];
      foreach ($products as $product) {
        if (floatval($product->price) <= floatval($maxPrice)) {
          $filteredProducts[] = $product;
        }
      }
      $products = $filteredProducts;
    }

    return $products;
  }
}