@extends('_layouts.admin')

{!! Datatable::table()
   ->addColumn('id','Name')       // these are the column headings to be shown
   ->setUrl(route('api.users'))   // this is the route where data will be retrieved
   ->render() !!}