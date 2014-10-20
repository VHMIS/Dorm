<?php

if($sessionLoginError)
{
    echo '-99999';
    exit();
}

if($_ssvUri['params']['action'] == 'update')
{
    $id = $rg->GetVar($_POST['id'], '');
    $name = $rg->GetVar($_POST['name'], '', true);
    $area = $rg->GetVar($_POST['area'], '');
    $sex = $rg->GetVar($_POST['sex'], '');
    $type = $rg->GetVar($_POST['type'], '');

    if($id == '' || $name == '' || $type == '' || $area == '' || $sex == '')
    {
        echo '-88888';
        exit();
    }

    if(!$validate->isDigit($id))
    {
        echo '-77777';
        exit();
    }

    //Get row
    if($rows = $dbRoom->find($id))
    {
        $rows = $rows[0];
        $rows->name = $name;
        $rows->type = $type;
        $rows->area = $area;
        $rows->gender = $sex;
        $rows->save();
        echo 'ok';
        exit();
    }
    else
    {
        echo '-666666';
        exit();
    }
}
elseif($_ssvUri['params']['action'] == 'new')
{
    $name = $rg->GetVar($_POST['name'], '', true);
    $area = $rg->GetVar($_POST['area'], '');
    $sex = $rg->GetVar($_POST['sex'], '');
    $type = $rg->GetVar($_POST['type'], '');

    if($name == '' || $type == '' || $area == '' || $sex == '')
    {
        echo '-88888';
        exit();
    }

    $row = $dbRoom->fetchNew();

    $row->name = $name;
    $row->type = $type;
    $row->area = $area;
    $row->gender = $sex;

    $row->save();
    echo $row->id;
    exit();
}