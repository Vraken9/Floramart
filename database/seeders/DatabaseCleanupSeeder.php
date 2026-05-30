<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class DatabaseCleanupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // STEP 1: Count and delete old products
        // Specifically targeting products where image_path does not start with 'products/bunga_'
        $oldProductsQuery = Product::where('image_path', 'not like', 'products/bunga_%')
                                   ->orWhereNull('image_path');
                                   
        $oldProductsCount = $oldProductsQuery->count();
        
        // Execute the deletion
        $oldProductsQuery->delete();
        
        // STEP 2: Count and delete unused categories (must be done after product deletion due to foreign keys)
        $unusedCategoriesQuery = Category::doesntHave('products');
        
        $unusedCategoriesCount = $unusedCategoriesQuery->count();
        
        // Execute the deletion
        $unusedCategoriesQuery->delete();
        
        // Output results to the terminal
        $this->command->info("🧹 Database Cleanup Completed!");
        $this->command->warn("Deleted Old/Dummy Products: {$oldProductsCount}");
        $this->command->warn("Deleted Unused Categories: {$unusedCategoriesCount}");
        
        $remainingProducts = Product::count();
        $remainingCategories = Category::count();
        
        $this->command->info("✅ Remaining Products: {$remainingProducts}");
        $this->command->info("✅ Remaining Categories: {$remainingCategories}");
    }
}
