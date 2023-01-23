# google-maps
Single page app that allows you to draw markers on Google Maps and save them in a JSON file

## How to use

1. Clone the repo to your local PC.
2. Run **composer require** to get dependencies (the app uses Slim Framework and Twig)
3. Create **.env** file in the root dir. Use **.env.example** as a template. Paste your google api key in the **.env** file. Alternatively, paste the api key directly in the **config.php** file.
4. Open the app in your browser. By default, all markers are saved to the **markers.json** file in the root dir.
