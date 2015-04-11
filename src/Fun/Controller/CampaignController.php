<?php

namespace Fun\Controller;


class CampaignController {
  public function index() {
    return "This is the index page";
  }

  public function store() {
    return "This is a post to the store method.";
  }

  public function show($id) {
    return "You passed in the number: $id";
  }

  public function edit($id) {
    return "You passed in the number: $id to be edited";
  }
}