@extends('errors::layout')

@section('title', 'Service Unavailable')

@section('message', $exception->getMessage())
