<?php

namespace Modules\Permission\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create roles
        $roles = [
            'super_admin' => 'مدیر ارشد'
        ];

        foreach ($roles as $name => $label) {
            Role::query()->firstOrCreate(
                ['name' => $name],
                ['label' => $label, 'guard_name' => 'admin-api']
            );
        }

        //create permissions
        $permissions = [
            'view dashboard stats' => 'مشاهده آمارهای داشبورد',
            
            //admins
            'view admins' => 'مشاهده ادمین ها',
            'create admins' => 'ایجاد ادمین ها',
            'edit admins' => 'ویرایش ادمین ها',
            'delete admins' => 'حذف ادمین ها',
        
            //users
            'view customers' => 'مشاهده مشتریان',
            'create customers' => 'ایجاد مشتریان',
            'edit customers' => 'ویرایش مشتریان',
            'delete customers' => 'حذف مشتریان',
        
            //settings
            'view settings' => 'مشاهده تنظیمات',
            'create settings' => 'ایجاد تنظیمات',
            'edit settings' => 'ویرایش تنظیمات',
        
            // //carts
            // 'view carts' => 'مشاهده سبد خرید',
            // 'create carts' => 'ایجاد سبد خرید',
            // 'edit carts' => 'ویرایش سبد خرید',
            // 'delete carts' => 'ویرایش سبد خرید',
        
            //categories
            'view categories' => 'مشاهده دسته بندی ها',
            'create categories' => 'ایجاد دسته بندی',
            'edit categories' => 'ویرایش دسته بندی',
            'delete categories' => 'حذف دسته بندی',

            //sliders
            'view sliders' => 'مشاهده اسلایدر ها',
            'create sliders' => 'ایجاد اسلایدر',
            'edit sliders' => 'ویرایش اسلایدر',
            'delete sliders' => 'حذف اسلایدر',
        
            //products
            'view products' => 'مشاهده محصول ها',
            'create products' => 'ایجاد محصول',
            'edit products' => 'ویرایش محصول',
            'delete products' => 'حذف محصول',
    
            //specifications
            'view specifications' => 'مشاهده مشخصات ها',
            'create specifications' => 'ایجاد مشخصات',
            'edit specifications' => 'ویرایش مشخصات',
            'delete specifications' => 'حذف مشخصات',
            // //addresses
            // 'view addresses' => 'مشاهده آدرس ها',
            // 'create addresses' => 'ایجاد آدرس',
            // 'edit addresses' => 'ویرایش آدرس',
            // 'delete addresses' => 'حذف آدرس',
        
            //cities
            'view cities' => 'مشاهده شهر ها',
            'create cities' => 'ایجاد شهر',
            'edit cities' => 'ویرایش شهر',
            'delete cities' => 'حذف شهر',

            //Province
            'view province' => 'مشاهده استان ها',
            //order
            'view orders' => 'مدیریت سفارش ها',
            'show orders' => 'نمایش سفارش ها',
            'update orders' => 'ویرایش سفارش ها',

        ];

        foreach ($permissions as $name => $label) {
            Permission::query()->firstOrCreate(
                ['name' => $name],
                ['label' => $label, 'guard_name' => 'admin-api']
            );
        }
    }
}
