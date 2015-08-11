<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Shop\Api;
use Common\Api\Api;
use Common\Model\AddressModel;

class AddressApi extends Api{
    /**
     * 添加
     */
    const ADD = "Shop/Address/add";
    /**
     * 保存
     */
    const SAVE = "Shop/Address/save";
    /**
     * 保存根据ID主键
     */
    const SAVE_BY_ID = "Shop/Address/saveByID";

    /**
     * 删除
     */
    const DELETE = "Shop/Address/delete";

    /**
     * 查询
     */
    const QUERY = "Shop/Address/query";

    const QUERY_NO_PAGING="Shop/Address/queryNoPaging";
    /**
     * 查询一条数据
     */
    const GET_INFO = "Shop/Address/getInfo";

    const QUERY_WITH_CITY_WITH_AREA = "Shop/Address/queryWithCityWithArea";
	protected function _init(){
		$this->model = new AddressModel();
	}


    /**
     * 查询地址
     * @param $map
     * @return array
     */
    public function queryWithCityWithArea($map){
        $query = $this->model;
        if(!is_null($map)){
            $query = $query->where($map);
        }

        $list = $query->alias('a')->join('left join common_city b ON a.city = b.cityID')->join('left join common_area c ON a.area=c.areaID')->join('left join common_province d ON a.province=d.provinceID')->field('a.id,a.uid,a.country,b.city,d.province,a.detailinfo,c.area,a.contactname,a.mobile,a.wxno') -> select();


        if ($list === false) {
            $error = $this -> model -> getDbError();
            return $this -> apiReturnErr($error);
        }

        return $this -> apiReturnSuc($list);

    }
	
	
}

