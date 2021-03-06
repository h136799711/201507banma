<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/24
 * Time: 15:44
 */

namespace Api\Controller;

use Think\Controller;

class AvatarController extends Controller
{

    protected $default = "iVBORw0KGgoAAAANSUhEUgAAAKAAAACgCAIAAAAErfB6AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAxJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MTk1RDAzRkEyOUQ0MTFFNThENTc5RkQ5MEIwQkVDRTIiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MTk1RDAzRjkyOUQ0MTFFNThENTc5RkQ5MEIwQkVDRTIiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiBNYWNpbnRvc2giPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0iMEY2REZDRDFEMkI0NDk2ODI0OUQ0QUUzNEEwNTQ4MkIiIHN0UmVmOmRvY3VtZW50SUQ9IjBGNkRGQ0QxRDJCNDQ5NjgyNDlENEFFMzRBMDU0ODJCIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+ZyF+oQAAD39JREFUeNrsXelTE80TXjABwhlEAQUPVA4VtTyq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";

    public function index()
    {

        if (IS_GET) {
            $uid = I('get.uid', 0);

            $Picture = D('UserPicture');
            $result = $Picture->where(array('uid' => $uid, 'type' => 'avatar'))->order('create_time desc')->find();

            if ($result === false || empty($result['imgurl'])) {
                header('Content-type: image/png');
                echo base64_decode($this->default);
                exit();
            }

            $imgurl = $result['imgurl'];

            $image = @file_get_contents($imgurl);
            if ($image == false) {
                header('Content-type: image/png');
                echo base64_decode($this->default);
                exit();
            }
            header('Content-type: image/'.$result['ext']);
            echo $image;
            exit();
        }

    }

}