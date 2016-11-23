<?php
$foods = array();

$foods[] = array(
	'id'=>1,
	'cate'=>1,
	'name'=>'糖醋排骨',
	'avatar'=>'img/1.jpg',
	'avatar_min'=>'img/1_min.jpg',
	'price'=>18.9,
	'recipe'=>'新鲜排骨,米醋,白砂糖,干辣椒',
	'taste'=>array('酸', '酸甜', '甜')
);
$foods[] = array(
	'id'=>2,
	'cate'=>1,
	'name'=>'红烧肉',
	'avatar'=>'img/2.jpg',
	'avatar_min'=>'img/2_min.jpg',
	'price'=>18.9,
	'recipe'=>'五花肉,白砂糖,酱油,姜,蒜',
	'taste'=>array('微辣', '中辣', '超辣', '特辣')
);
$foods[] = array(
	'id'=>3,
	'cate'=>1,
	'name'=>'农家小炒肉',
	'avatar'=>'img/3.jpg',
	'avatar_min'=>'img/3_min.jpg',
	'price'=>12.9,
	'recipe'=>'五花肉,尖椒,大蒜,干辣椒',
	'taste'=>array('微辣', '中辣', '超辣', '特辣')
);
$foods[] = array(
	'id'=>4,
	'cate'=>2,
	'name'=>'青椒肉丝',
	'avatar'=>'img/4.jpg',
	'avatar_min'=>'img/4_min.jpg',
	'price'=>13.0,
	'taste'=>array('微辣', '中辣', '超辣', '特辣')
);
$foods[] = array(
	'id'=>5,
	'cate'=>2,
	'name'=>'西红柿炒鸡蛋',
	'avatar'=>'img/5.jpg',
	'avatar_min'=>'img/5_min.jpg',
	'price'=>12.0
);

$foodCate = array(
	array('id'=>1, 'title'=>'推荐'),
	array('id'=>2, 'title'=>'热菜'),
	array('id'=>3, 'title'=>'凉菜'),
	array('id'=>4, 'title'=>'主食'),
	array('id'=>5, 'title'=>'酒水')
);

gen_json_file('foods', $foods);
gen_json_file('foodCate', $foodCate);

function gen_json_file($filename='json', $data) {
	file_put_contents($filename.'.min.json', json_encode(array('code'=>200, 'data'=>$data)));
}