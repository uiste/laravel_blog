<?php

use Illuminate\Database\Seeder;

class LinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'link_name' => 'uiste技术博客',
                'link_title'=> '专注于技术分享，实战经验总结',
                'link_url'  => 'http://blog.uiste.com',
                'link_order'=> 1,
            ],
            [
                'link_name' => 'uiste生活博客',
                'link_title'=> '分享生活，欣赏一切美好的事物',
                'link_url'  => 'http://www.uiste.com',
                'link_order'=> 2,
            ]
        ];
        DB::table('links') -> insert($data);
    }
}
