<?php
namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;


trait GenerateUsernameTrait
{
    protected function generateUsername($name)
    {
        $username = $this->strtocap($name);
        $userRows  = User::whereRaw("name REGEXP '^{$username}([0-9]*)?$'")->pluck('username')->toArray();
        if(count($userRows) > 0) {
            $userRows = str_replace($username, '', $userRows);
            $i=1;
            while(in_array($i, $userRows)) {
                $i++;
            }
            $username = trim($username.$i);
        }

        return strtok(trim(substr($username, 0, 13)),  ' ').strtok(" ");
    }


    protected function strtocap($arg)
    {
        $finalStr = array();

        $argX = explode(" ",$arg);
        if(is_array($argX)){
            foreach($argX as $v){
                $finalStr[] = ucfirst($v);
            }
        }

        return implode(" ",$finalStr);
    }
}
