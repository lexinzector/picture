# Picture
Library for working with images (cropping and reducing, converting to base64, etc.)

## Possibilities
- proportionally reduce the image size in width or height
- reduce the image by percentage
- fit the image into a frame of a certain size
- arbitrary image resizing
- change image quality
- output the generated image without saving
- saving the image on the server
- assigning access rights to the image

## Requirements
PHP >= 5.6.0

## Installation
run in console:
```
composer require lexinzector/picture
```

## Example
```php
// resize the image proportionally and save it on the server without changing the file extension
// the resulting image will not exceed 400 pixels in height and 300 in width
// that is, it will automatically fit into the required dimensions
$new_image = new scoePicture('url or path to file');
$new_image->autoimageresize(300, 400);
$new_image->imagesave($new_image->image_type, 'folder on the server');
$new_image->imageout();

// output to the screen without changing the file extension
$new_image = new scoePicture('url or path to file');
$new_image->autoimageresize(300, 400);
$new_image->imagesave($new_image->image_type, null);
$new_image->imageout();

// save on the server and select the output file type
$new_image = new scoePicture('url or path to file');
$new_image->autoimageresize(300, 400);
$new_image->imagesave('png', 'folder on the server');
$new_image->imageout();

// compress the file and display it on the screen
$new_image = new scoePicture('url or path to file');
$new_image->autoimageresize(300, 400);
$new_image->imagesave('jpeg', null, 75);
$new_image->imageout();

// save on the server, compress and set access rights
$new_image = new scoePicture('url or path to file');
$new_image->autoimageresize(300, 400);
$new_image->imagesave('jpeg', 'folder on the server', 75, 0777);
$new_image->imageout();

// reduce the image width
$new_image = new scoePicture('url or path to file');
$new_image->imageresizewidth(300);
$new_image->imagesave($new_image->image_type, 'folder on the server');
$new_image->imageout();

// reduce the image height
$new_image = new scoePicture('url or path to file');
$new_image->imageresizeheight(400);
$new_image->imagesave($new_image->image_type, 'folder on the server');
$new_image->imageout();

// arbitrarily reduce the image without maintaining the proportions
$new_image = new scoePicture('url or path to file');
$new_image->imageresize(300, 400);
$new_image->imagesave($new_image->image_type, 'folder on the server');
$new_image->imageout();

// reduce the image by percentage
$new_image = new scoePicture('url or path to file');
$new_image->percentimagereduce(30);
$new_image->imagesave($new_image->image_type, 'folder on the server');
$new_image->imageout();

// reduce the image without maintaining the proportions without saving the image on disk for output in base64 format
$new_image = new scoePicture('url or path to file');
$new_image->imageresize(300, 400);
$new_image->imagesave($new_image->image_type, 'out');
$new_image->imageout();
$dataUri = "data:image/jpeg;base64,".base64_encode($new_image->image_contents);
```
