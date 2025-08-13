<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Expedition;
use App\Models\IndustrySector;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_many_expeditions(): void
    {
        $user = User::factory()->create();
        $expedition = Expedition::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->expeditions->contains($expedition));
        $this->assertEquals(1, $user->expeditions->count());
    }

    public function test_expedition_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $expedition = Expedition::factory()->create(['user_id' => $user->id]);

        $this->assertEquals($user->id, $expedition->user->id);
    }

    public function test_industry_sector_has_many_expeditions(): void
    {
        $industrySector = IndustrySector::factory()->create();
        $expedition = Expedition::factory()->create(['industry_sector_id' => $industrySector->id]);

        $this->assertTrue($industrySector->expeditions->contains($expedition));
        $this->assertEquals(1, $industrySector->expeditions->count());
    }

    public function test_expedition_belongs_to_industry_sector(): void
    {
        $industrySector = IndustrySector::factory()->create();
        $expedition = Expedition::factory()->create(['industry_sector_id' => $industrySector->id]);

        $this->assertEquals($industrySector->id, $expedition->industrySector->id);
    }

    public function test_category_has_many_expeditions(): void
    {
        $category = Category::inRandomOrder()->first();
        $expedition = Expedition::factory()->create(['category_id' => $category->id]);

        $this->assertTrue($category->expeditions->contains($expedition));
        $this->assertEquals(1, $category->expeditions->count());
    }

    public function test_expedition_belongs_to_category(): void
    {
        $category = Category::inRandomOrder()->first();
        $expedition = Expedition::factory()->create(['category_id' => $category->id]);

        $this->assertEquals($category->id, $expedition->category->id);
    }

    public function test_vendor_has_many_expeditions(): void
    {
        $vendor = Vendor::factory()->create();
        $expedition = Expedition::factory()->create(['vendor_id' => $vendor->id]);

        $this->assertTrue($vendor->expeditions->contains($expedition));
        $this->assertEquals(1, $vendor->expeditions->count());
    }

    public function test_expedition_belongs_to_vendor(): void
    {
        $vendor = Vendor::factory()->create();
        $expedition = Expedition::factory()->create(['vendor_id' => $vendor->id]);

        $this->assertEquals($vendor->id, $expedition->vendor->id);
    }
}
