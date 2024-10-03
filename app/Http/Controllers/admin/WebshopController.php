<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class WebshopController extends Controller
{

  private function load_data(){

    $path = resource_path('views/website/website.json');

    // Get the file contents
    $json = File::get($path);

    // Decode the JSON data
    $data = json_decode($json, true);

    return $data;
  }

  private function update_data($data){

    $path = resource_path('views/website/website.json');

    $newJson = json_encode($data, JSON_PRETTY_PRINT);

    File::put($path, $newJson);
  }
  /** section Hero */
  public function hero()
  {

    $data = $this->load_data();

    $data = $data['hero'];

    return view('admin.webshop.hero',compact('data'));
  }

  public function hero_update(Request $request){

    $data = $this->load_data();

    // update image if loaded
    if($request->hasFile('image') && $request->file('image')->isValid()) {
      
      // delete image if exist
      if (Storage::exists($data['hero']['image'])) {
        Storage::delete($data['hero']['image']);
      }
      // store the new image
      $data['hero']['image'] = $request->file('image')->store('images');
    }


    $data['hero']['title'] = $request->title;
    $data['hero']['subTitle'] = $request->subTitle;
    $data['hero']['buttonTitle'] = $request->buttonTitle;
    $data['hero']['videoTitle'] = $request->videoTitle;

    if($request->enable != null){
      $data['hero']['enable'] = true;
    }
    else{
      $data['hero']['enable'] = false;
    }

    $this->update_data($data);
    
    return redirect()->back()->with('success', "Hero section updated");

  }

  /** section about us */

  public function aboutus()
  {

    $data = $this->load_data();

    $data = $data['aboutus'];

    return view('admin.webshop.aboutus',compact('data'));
  }

  public function aboutus_update(Request $request){

    $data = $this->load_data();

    // update image if loaded
    if($request->hasFile('image') && $request->file('image')->isValid()) {
      
      // delete image if exist
      if (Storage::exists($data['aboutus']['image'])) {
        Storage::delete($data['aboutus']['image']);
      }
      // store the new image
      $data['aboutus']['image'] = $request->file('image')->store('images');
    }


    $data['aboutus']['title'] = $request->title;
    $data['aboutus']['subTitle'] = $request->subTitle;
    $data['aboutus']['paragraphs'] = $request->paragraphs;

    if($request->enable != null){
      $data['aboutus']['enable'] = true;
    }
    else{
      $data['aboutus']['enable'] = false;
    }

    $this->update_data($data);
    
    return redirect()->back()->with('success', "About us section updated");

  }

  /** section features */

  public function features()
  {

    $data = $this->load_data();

    $data = $data['features'];

    return view('admin.webshop.features',compact('data'));
  }

  public function features_update(Request $request){

    $data = $this->load_data();


    $data['features']['title'] = $request->title;
    $data['features']['subTitle'] = $request->subTitle;
    $data['features']['features'] = $request->features;

    if($request->enable != null){
      $data['features']['enable'] = true;
    }
    else{
      $data['features']['enable'] = false;
    }

    $this->update_data($data);
    
    return redirect()->back()->with('success', "Features section updated");

  }

  /** section pricing */

  public function pricing()
  {

    $data = $this->load_data();

    $data = $data['pricing'];

    return view('admin.webshop.pricing',compact('data'));
  }

  public function pricing_update(Request $request){

    $data = $this->load_data();


    $data['pricing']['title'] = $request->title;
    $data['pricing']['subTitle'] = $request->subTitle;

    if($request->enable != null){
      $data['pricing']['enable'] = true;
    }
    else{
      $data['pricing']['enable'] = false;
    }

    $this->update_data($data);
    
    return redirect()->back()->with('success', "Pricing section updated");

  }

  /** section pricing */

  public function cta()
  {

    $data = $this->load_data();

    $data = $data['cta'];

    return view('admin.webshop.cta',compact('data'));
  }

  public function cta_update(Request $request){

    $data = $this->load_data();


    $data['cta']['title'] = $request->title;
    $data['cta']['subTitle'] = $request->subTitle;
    $data['cta']['buttonTitle'] = $request->buttonTitle;

    if($request->enable != null){
      $data['cta']['enable'] = true;
    }
    else{
      $data['cta']['enable'] = false;
    }

    $this->update_data($data);
    
    return redirect()->back()->with('success', "Call to Action section updated");

  }

  /** section Faq */

  public function faq()
  {

    $data = $this->load_data();

    $data = $data['faq'];

    return view('admin.webshop.faq',compact('data'));
  }

  public function faq_update(Request $request){

    $data = $this->load_data();


    $data['faq']['title'] = $request->title;
    $data['faq']['subTitle'] = $request->subTitle;
    $data['faq']['questions'] = $request->questions;

    if($request->enable != null){
      $data['faq']['enable'] = true;
    }
    else{
      $data['faq']['enable'] = false;
    }

    $this->update_data($data);
    
    return redirect()->back()->with('success', "FAQ section updated");

  }
    /** section contact */

    public function contact()
    {
  
      $data = $this->load_data();
  
      $data = $data['contact'];
  
      return view('admin.webshop.contact',compact('data'));
    }
  
    public function contact_update(Request $request){
      
      $data = $this->load_data();
  
  
      $data['contact']['title'] = $request->title;
      $data['contact']['subTitle'] = $request->subTitle;

      if($request->enable != null){
        $data['contact']['enable'] = true;
      }
      else{
        $data['contact']['enable'] = false;
      }
  
  
      $this->update_data($data);
      
      return redirect()->back()->with('success', "Contact section updated");
  
    }

    public function colors(Request $request){
      return view('admin.webshop.colors');
    }

    public function colors_update(Request $request){
      dd($request);
    }

}
