# Magento 2(Update products' position of a category at once)
1. This module consists of a file uploader that collects the data and updates the position of products mentioned in the CSV, also stores it in custom table named **product_csv**.
2. Also, the data collected in the custom table is shown through a grid in the magento admin with the grid named as **PRODUCT POSITION**.
## Basic Flow of the module
- Firstly, after cloning the git and extracting the folder wrap it inside folder **ProductPosition** and again wrap this folder in **Task** so inshort, create your directory as magento-root-directory/app/code/Task/ProductPosition/cloned_directory.
- After that open the magento root directory in terminal and hit the following commands,
  - `sudo php bin/magento module:enable Task_ProductPosition`
  - `sudo php bin/magento setup:upgrade`
  - `sudo php bin/magento setup:di:compile`
  - `sudo php bin/magento setup:static-content:deploy -f`
  - `sudo php bin/magento indexer:reindex` (optional)
  - `sudo php bin/magento cache:flush`
  - `sudo chmod 777 -R var/ pub/ generated/`
- Now, open your web browser and type in the following link to open magento admin (assuming for localhost or else type in https://your-magento-from-ftp/admin) **localhost/magento-root-directory/admin** you will see a grid named **PRODUCT POSITION**.
- After that click on the grid and then click on Manage Records it will show the grid view for logged entries and also a button to upload file i.e. **Add Record** in that select the category desired for updation of products and then upload the CSV file and click on **Save**.
- You will be redirected to the grid page with the new entry log of your file location and category ID whose products' position is updated with a success message
**>> To create the proper format of CSV file create two columns in it with first column consisting of SKU of the products of a category and second column consisting of position value which should be numeric in nature.**
- To cross verify go to Catalog->Categories and select the desired category and click on **Products in category** tab and search for the SKU mentioned in the CSV file and see the position value which should have already been updated as per the values in the file.
- To validate more you can check out the product_csv table as well as magento table for position of products.

**Note :**
- Be sure to run command `sudo php bin/magento indexer:reindex` in magento root terminal for the feature's effect on frontend as well.
- Don't try to proceed to Save without selecting a category since doing so will result in the redirection of same page with a warning as well as the form will reset.
- Don't try to Save a different or invalid CSV file for a certain category since doing so will result in the redirection of same page with a warning as well as the form will reset.
- Beware changing the module and vendor name since it can be a bit hectic and will require a keen vision on every file.

### Happy Coding :)
