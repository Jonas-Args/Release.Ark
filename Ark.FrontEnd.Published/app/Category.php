<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function subcategories() {
        return $this->hasMany(SubCategory::class );
    }

    public function products() {
        return $this->hasMany(Product::class );
    }

    public function subsubcategories() {
        return $this->hasMany(SubSubCategory::class );
    }

    public function homecategory() {
        return $this->hasMany(HomeCategory::class );
    }
}
