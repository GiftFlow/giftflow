<?php
/*
 * Copyright (c) 2010 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

require_once 'service/apiModel.php';
require_once 'service/apiService.php';
require_once 'service/apiServiceRequest.php';


  /**
   * The "products" collection of methods.
   * Typical usage is:
   *  <code>
   *   $shoppingService = new apiShoppingService(...);
   *   $products = $shoppingService->products;
   *  </code>
   */
  class ProductsServiceResource extends apiServiceResource {


    /**
     * Returns a list of products and content modules (products.list)
     *
     * @param string $source Query source
     * @param array $optParams Optional parameters. Valid optional parameters are listed below.
     *
     * @opt_param bool sayt.useGcsConfig Google Internal
     * @opt_param string rankBy Ranking specification
     * @opt_param bool debug.enableLogging Google Internal
     * @opt_param bool facets.enabled Whether to return facet information
     * @opt_param bool relatedQueries.useGcsConfig This parameter is currently ignored
     * @opt_param bool promotions.enabled Whether to return promotion information
     * @opt_param bool debug.enabled Google Internal
     * @opt_param string facets.include Facets to include (applies when useGcsConfig == false)
     * @opt_param string productFields Google Internal
     * @opt_param string channels Channels specification
     * @opt_param string currency Currency restriction (ISO 4217)
     * @opt_param string startIndex Index (1-based) of first product to return
     * @opt_param string facets.discover Facets to discover
     * @opt_param bool debug.searchResponse Google Internal
     * @opt_param string crowdBy Crowding specification
     * @opt_param bool spelling.enabled Whether to return spelling suggestions
     * @opt_param bool debug.geocodeRequest Google Internal
     * @opt_param bool spelling.useGcsConfig This parameter is currently ignored
     * @opt_param bool shelfSpaceAds.useGcsConfig This parameter is currently ignored
     * @opt_param bool shelfSpaceAds.enabled Whether to return shelf space ads
     * @opt_param string useCase One of CommerceSearchUseCase, ShoppingApiUseCase
     * @opt_param string location Location used to determine tax and shipping
     * @opt_param string taxonomy Taxonomy name
     * @opt_param bool debug.rdcRequest Google Internal
     * @opt_param string categories.include Category specification
     * @opt_param string boostBy Boosting specification
     * @opt_param bool redirects.useGcsConfig Whether to return redirect information as configured in the GCS account
     * @opt_param bool safe Whether safe search is enabled. Default: true
     * @opt_param bool categories.useGcsConfig This parameter is currently ignored
     * @opt_param string maxResults Maximum number of results to return
     * @opt_param bool facets.useGcsConfig Whether to return facet information as configured in the GCS account
     * @opt_param bool categories.enabled Whether to return category information
     * @opt_param string attributeFilter Comma separated list of attributes to return
     * @opt_param bool sayt.enabled Google Internal
     * @opt_param string thumbnails Image thumbnails specification
     * @opt_param string language Language restriction (BCP 47)
     * @opt_param string country Country restriction (ISO 3166)
     * @opt_param bool debug.geocodeResponse Google Internal
     * @opt_param string restrictBy Restriction specification
     * @opt_param bool debug.rdcResponse Google Internal
     * @opt_param string q Search query
     * @opt_param string shelfSpaceAds.maxResults The maximum number of shelf space ads to return
     * @opt_param bool redirects.enabled Whether to return redirect information
     * @opt_param bool debug.searchRequest Google Internal
     * @opt_param bool relatedQueries.enabled Whether to return related queries
     * @opt_param string minAvailability
     * @opt_param bool promotions.useGcsConfig Whether to return promotion information as configured in the GCS account
     * @return Products
     */
    public function listProducts($source, $optParams = array()) {
      $params = array('source' => $source);
      $params = array_merge($params, $optParams);
      $data = $this->__call('list', array($params));
      if ($this->useObjects()) {
        return new Products($data);
      } else {
        return $data;
      }
    }
    /**
     * Returns a single product (products.get)
     *
     * @param string $source Query source
     * @param string $accountId Merchant center account id
     * @param string $productIdType Type of productId
     * @param string $productId Id of product
     * @param array $optParams Optional parameters. Valid optional parameters are listed below.
     *
     * @opt_param string categories.include Category specification
     * @opt_param bool recommendations.enabled Whether to return recommendation information
     * @opt_param bool debug.enableLogging Google Internal
     * @opt_param string taxonomy Merchant taxonomy
     * @opt_param bool categories.useGcsConfig This parameter is currently ignored
     * @opt_param bool debug.searchResponse Google Internal
     * @opt_param bool debug.enabled Google Internal
     * @opt_param string recommendations.include Recommendation specification
     * @opt_param bool categories.enabled Whether to return category information
     * @opt_param string location Location used to determine tax and shipping
     * @opt_param bool debug.searchRequest Google Internal
     * @opt_param string attributeFilter Comma separated list of attributes to return
     * @opt_param bool recommendations.useGcsConfig This parameter is currently ignored
     * @opt_param string productFields Google Internal
     * @opt_param string thumbnails Thumbnail specification
     * @return Product
     */
    public function get($source, $accountId, $productIdType, $productId, $optParams = array()) {
      $params = array('source' => $source, 'accountId' => $accountId, 'productIdType' => $productIdType, 'productId' => $productId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('get', array($params));
      if ($this->useObjects()) {
        return new Product($data);
      } else {
        return $data;
      }
    }
  }



/**
 * Service definition for Shopping (v1).
 *
 * <p>
 * Lets you search over product data
 * </p>
 *
 * <p>
 * For more information about this service, see the
 * <a href="http://code.google.com/apis/shopping/search/v1/getting_started.html" target="_blank">API Documentation</a>
 * </p>
 *
 * @author Google, Inc.
 */
class apiShoppingService extends apiService {
  public $products;
  /**
   * Constructs the internal representation of the Shopping service.
   *
   * @param apiClient apiClient
   */
  public function __construct(apiClient $apiClient) {
    $this->rpcPath = '/rpc';
    $this->restBasePath = '/shopping/search/v1/';
    $this->version = 'v1';
    $this->serviceName = 'shopping';
    $this->io = $apiClient->getIo();

    $apiClient->addService($this->serviceName, $this->version);
    $this->products = new ProductsServiceResource($this, $this->serviceName, 'products', json_decode('{"methods": {"list": {"parameters": {"sayt.useGcsConfig": {"type": "boolean", "location": "query"}, "debug.geocodeResponse": {"type": "boolean", "location": "query"}, "debug.enableLogging": {"type": "boolean", "location": "query"}, "facets.enabled": {"type": "boolean", "location": "query"}, "relatedQueries.useGcsConfig": {"type": "boolean", "location": "query"}, "promotions.enabled": {"type": "boolean", "location": "query"}, "debug.enabled": {"type": "boolean", "location": "query"}, "facets.include": {"type": "string", "location": "query"}, "productFields": {"type": "string", "location": "query"}, "channels": {"type": "string", "location": "query"}, "currency": {"type": "string", "location": "query"}, "startIndex": {"format": "uint32", "type": "integer", "location": "query"}, "facets.discover": {"type": "string", "location": "query"}, "debug.searchResponse": {"type": "boolean", "location": "query"}, "crowdBy": {"type": "string", "location": "query"}, "spelling.enabled": {"type": "boolean", "location": "query"}, "debug.geocodeRequest": {"type": "boolean", "location": "query"}, "source": {"required": true, "type": "string", "location": "path"}, "shelfSpaceAds.useGcsConfig": {"type": "boolean", "location": "query"}, "shelfSpaceAds.enabled": {"type": "boolean", "location": "query"}, "spelling.useGcsConfig": {"type": "boolean", "location": "query"}, "useCase": {"type": "string", "location": "query"}, "location": {"type": "string", "location": "query"}, "taxonomy": {"type": "string", "location": "query"}, "debug.rdcRequest": {"type": "boolean", "location": "query"}, "categories.include": {"type": "string", "location": "query"}, "debug.searchRequest": {"type": "boolean", "location": "query"}, "boostBy": {"type": "string", "location": "query"}, "safe": {"type": "boolean", "location": "query"}, "categories.useGcsConfig": {"type": "boolean", "location": "query"}, "maxResults": {"format": "uint32", "type": "integer", "location": "query"}, "facets.useGcsConfig": {"type": "boolean", "location": "query"}, "categories.enabled": {"type": "boolean", "location": "query"}, "attributeFilter": {"type": "string", "location": "query"}, "sayt.enabled": {"type": "boolean", "location": "query"}, "thumbnails": {"type": "string", "location": "query"}, "language": {"type": "string", "location": "query"}, "redirects.useGcsConfig": {"type": "boolean", "location": "query"}, "rankBy": {"type": "string", "location": "query"}, "restrictBy": {"type": "string", "location": "query"}, "debug.rdcResponse": {"type": "boolean", "location": "query"}, "q": {"type": "string", "location": "query"}, "shelfSpaceAds.maxResults": {"format": "uint32", "type": "integer", "location": "query"}, "redirects.enabled": {"type": "boolean", "location": "query"}, "country": {"type": "string", "location": "query"}, "relatedQueries.enabled": {"type": "boolean", "location": "query"}, "minAvailability": {"enum": ["inStock", "limited", "outOfStock", "unknown"], "type": "string", "location": "query"}, "promotions.useGcsConfig": {"type": "boolean", "location": "query"}}, "id": "shopping.products.list", "httpMethod": "GET", "path": "{source}/products", "response": {"$ref": "Products"}}, "get": {"parameters": {"categories.include": {"type": "string", "location": "query"}, "recommendations.enabled": {"type": "boolean", "location": "query"}, "debug.enableLogging": {"type": "boolean", "location": "query"}, "thumbnails": {"type": "string", "location": "query"}, "recommendations.include": {"type": "string", "location": "query"}, "taxonomy": {"type": "string", "location": "query"}, "productIdType": {"required": true, "type": "string", "location": "path"}, "categories.useGcsConfig": {"type": "boolean", "location": "query"}, "attributeFilter": {"type": "string", "location": "query"}, "debug.enabled": {"type": "boolean", "location": "query"}, "source": {"required": true, "type": "string", "location": "path"}, "categories.enabled": {"type": "boolean", "location": "query"}, "location": {"type": "string", "location": "query"}, "debug.searchRequest": {"type": "boolean", "location": "query"}, "debug.searchResponse": {"type": "boolean", "location": "query"}, "recommendations.useGcsConfig": {"type": "boolean", "location": "query"}, "productFields": {"type": "string", "location": "query"}, "accountId": {"format": "uint32", "required": true, "type": "integer", "location": "path"}, "productId": {"required": true, "type": "string", "location": "path"}}, "id": "shopping.products.get", "httpMethod": "GET", "path": "{source}/products/{accountId}/{productIdType}/{productId}", "response": {"$ref": "Product"}}}}', true));
  }
}

class ShoppingModelProductJsonV1Inventories extends apiModel {

  public $distance;
  public $price;
  public $storeId;
  public $tax;
  public $shipping;
  public $currency;
  public $distanceUnit;
  public $availability;
  public $channel;

  public function setDistance($distance) {
    $this->distance = $distance;
  }

  public function getDistance() {
    return $this->distance;
  }
  
  public function setPrice($price) {
    $this->price = $price;
  }

  public function getPrice() {
    return $this->price;
  }
  
  public function setStoreId($storeId) {
    $this->storeId = $storeId;
  }

  public function getStoreId() {
    return $this->storeId;
  }
  
  public function setTax($tax) {
    $this->tax = $tax;
  }

  public function getTax() {
    return $this->tax;
  }
  
  public function setShipping($shipping) {
    $this->shipping = $shipping;
  }

  public function getShipping() {
    return $this->shipping;
  }
  
  public function setCurrency($currency) {
    $this->currency = $currency;
  }

  public function getCurrency() {
    return $this->currency;
  }
  
  public function setDistanceUnit($distanceUnit) {
    $this->distanceUnit = $distanceUnit;
  }

  public function getDistanceUnit() {
    return $this->distanceUnit;
  }
  
  public function setAvailability($availability) {
    $this->availability = $availability;
  }

  public function getAvailability() {
    return $this->availability;
  }
  
  public function setChannel($channel) {
    $this->channel = $channel;
  }

  public function getChannel() {
    return $this->channel;
  }
  
}


class ProductsStores extends apiModel {

  public $storeCode;
  public $name;
  public $storeId;
  public $telephone;
  public $location;
  public $address;

  public function setStoreCode($storeCode) {
    $this->storeCode = $storeCode;
  }

  public function getStoreCode() {
    return $this->storeCode;
  }
  
  public function setName($name) {
    $this->name = $name;
  }

  public function getName() {
    return $this->name;
  }
  
  public function setStoreId($storeId) {
    $this->storeId = $storeId;
  }

  public function getStoreId() {
    return $this->storeId;
  }
  
  public function setTelephone($telephone) {
    $this->telephone = $telephone;
  }

  public function getTelephone() {
    return $this->telephone;
  }
  
  public function setLocation($location) {
    $this->location = $location;
  }

  public function getLocation() {
    return $this->location;
  }
  
  public function setAddress($address) {
    $this->address = $address;
  }

  public function getAddress() {
    return $this->address;
  }
  
}


class ShoppingModelCategoryJsonV1 extends apiModel {

  public $url;
  public $shortName;
  public $parents;
  public $id;

  public function setUrl($url) {
    $this->url = $url;
  }

  public function getUrl() {
    return $this->url;
  }
  
  public function setShortName($shortName) {
    $this->shortName = $shortName;
  }

  public function getShortName() {
    return $this->shortName;
  }
  
  public function setParents($parents) {
    $this->parents = $parents;
  }

  public function getParents() {
    return $this->parents;
  }
  
  public function setId($id) {
    $this->id = $id;
  }

  public function getId() {
    return $this->id;
  }
  
}


class ShoppingModelProductJsonV1Images extends apiModel {

  public $link;
  public $thumbnails;

  public function setLink($link) {
    $this->link = $link;
  }

  public function getLink() {
    return $this->link;
  }
  
  public function setThumbnails(ShoppingModelProductJsonV1ImagesThumbnails $thumbnails) {
    $this->thumbnails = $thumbnails;
  }

  public function getThumbnails() {
    return $this->thumbnails;
  }
  
}


class ShoppingModelDebugJsonV1 extends apiModel {

  public $searchRequest;
  public $searchResponse;
  public $rdcResponse;

  public function setSearchRequest($searchRequest) {
    $this->searchRequest = $searchRequest;
  }

  public function getSearchRequest() {
    return $this->searchRequest;
  }
  
  public function setSearchResponse($searchResponse) {
    $this->searchResponse = $searchResponse;
  }

  public function getSearchResponse() {
    return $this->searchResponse;
  }
  
  public function setRdcResponse($rdcResponse) {
    $this->rdcResponse = $rdcResponse;
  }

  public function getRdcResponse() {
    return $this->rdcResponse;
  }
  
}


class ProductsFacetsBuckets extends apiModel {

  public $count;
  public $minExclusive;
  public $min;
  public $max;
  public $value;
  public $maxExclusive;

  public function setCount($count) {
    $this->count = $count;
  }

  public function getCount() {
    return $this->count;
  }
  
  public function setMinExclusive($minExclusive) {
    $this->minExclusive = $minExclusive;
  }

  public function getMinExclusive() {
    return $this->minExclusive;
  }
  
  public function setMin($min) {
    $this->min = $min;
  }

  public function getMin() {
    return $this->min;
  }
  
  public function setMax($max) {
    $this->max = $max;
  }

  public function getMax() {
    return $this->max;
  }
  
  public function setValue($value) {
    $this->value = $value;
  }

  public function getValue() {
    return $this->value;
  }
  
  public function setMaxExclusive($maxExclusive) {
    $this->maxExclusive = $maxExclusive;
  }

  public function getMaxExclusive() {
    return $this->maxExclusive;
  }
  
}


class Product extends apiModel {

  public $kind;
  public $product;
  public $selfLink;
  public $recommendations;
  public $debug;
  public $id;
  public $categories;

  public function setKind($kind) {
    $this->kind = $kind;
  }

  public function getKind() {
    return $this->kind;
  }
  
  public function setProduct(ShoppingModelProductJsonV1 $product) {
    $this->product = $product;
  }

  public function getProduct() {
    return $this->product;
  }
  
  public function setSelfLink($selfLink) {
    $this->selfLink = $selfLink;
  }

  public function getSelfLink() {
    return $this->selfLink;
  }
  
  public function setRecommendations(ProductRecommendations $recommendations) {
    $this->recommendations = $recommendations;
  }

  public function getRecommendations() {
    return $this->recommendations;
  }
  
  public function setDebug(ShoppingModelDebugJsonV1 $debug) {
    $this->debug = $debug;
  }

  public function getDebug() {
    return $this->debug;
  }
  
  public function setId($id) {
    $this->id = $id;
  }

  public function getId() {
    return $this->id;
  }
  
  public function setCategories(ShoppingModelCategoryJsonV1 $categories) {
    $this->categories = $categories;
  }

  public function getCategories() {
    return $this->categories;
  }
  
}


class ProductsFacets extends apiModel {

  public $count;
  public $displayName;
  public $name;
  public $buckets;
  public $property;
  public $type;
  public $unit;

  public function setCount($count) {
    $this->count = $count;
  }

  public function getCount() {
    return $this->count;
  }
  
  public function setDisplayName($displayName) {
    $this->displayName = $displayName;
  }

  public function getDisplayName() {
    return $this->displayName;
  }
  
  public function setName($name) {
    $this->name = $name;
  }

  public function getName() {
    return $this->name;
  }
  
  public function setBuckets(ProductsFacetsBuckets $buckets) {
    $this->buckets = $buckets;
  }

  public function getBuckets() {
    return $this->buckets;
  }
  
  public function setProperty($property) {
    $this->property = $property;
  }

  public function getProperty() {
    return $this->property;
  }
  
  public function setType($type) {
    $this->type = $type;
  }

  public function getType() {
    return $this->type;
  }
  
  public function setUnit($unit) {
    $this->unit = $unit;
  }

  public function getUnit() {
    return $this->unit;
  }
  
}


class ProductsShelfSpaceAds extends apiModel {

  public $product;

  public function setProduct(ShoppingModelProductJsonV1 $product) {
    $this->product = $product;
  }

  public function getProduct() {
    return $this->product;
  }
  
}


class ProductRecommendations extends apiModel {

  public $recommendationList;
  public $type;

  public function setRecommendationList(ProductRecommendationsRecommendationList $recommendationList) {
    $this->recommendationList = $recommendationList;
  }

  public function getRecommendationList() {
    return $this->recommendationList;
  }
  
  public function setType($type) {
    $this->type = $type;
  }

  public function getType() {
    return $this->type;
  }
  
}


class ProductsSpelling extends apiModel {

  public $suggestion;

  public function setSuggestion($suggestion) {
    $this->suggestion = $suggestion;
  }

  public function getSuggestion() {
    return $this->suggestion;
  }
  
}


class ProductRecommendationsRecommendationList extends apiModel {

  public $product;

  public function setProduct(ShoppingModelProductJsonV1 $product) {
    $this->product = $product;
  }

  public function getProduct() {
    return $this->product;
  }
  
}


class Products extends apiModel {

  public $promotions;
  public $kind;
  public $stores;
  public $currentItemCount;
  public $items;
  public $facets;
  public $itemsPerPage;
  public $redirects;
  public $nextLink;
  public $shelfSpaceAds;
  public $startIndex;
  public $etag;
  public $selfLink;
  public $relatedQueries;
  public $debug;
  public $spelling;
  public $previousLink;
  public $totalItems;
  public $id;
  public $categories;

  public function setPromotions(ProductsPromotions $promotions) {
    $this->promotions = $promotions;
  }

  public function getPromotions() {
    return $this->promotions;
  }
  
  public function setKind($kind) {
    $this->kind = $kind;
  }

  public function getKind() {
    return $this->kind;
  }
  
  public function setStores(ProductsStores $stores) {
    $this->stores = $stores;
  }

  public function getStores() {
    return $this->stores;
  }
  
  public function setCurrentItemCount($currentItemCount) {
    $this->currentItemCount = $currentItemCount;
  }

  public function getCurrentItemCount() {
    return $this->currentItemCount;
  }
  
  public function setItems(Product $items) {
    $this->items = $items;
  }

  public function getItems() {
    return $this->items;
  }
  
  public function setFacets(ProductsFacets $facets) {
    $this->facets = $facets;
  }

  public function getFacets() {
    return $this->facets;
  }
  
  public function setItemsPerPage($itemsPerPage) {
    $this->itemsPerPage = $itemsPerPage;
  }

  public function getItemsPerPage() {
    return $this->itemsPerPage;
  }
  
  public function setRedirects($redirects) {
    $this->redirects = $redirects;
  }

  public function getRedirects() {
    return $this->redirects;
  }
  
  public function setNextLink($nextLink) {
    $this->nextLink = $nextLink;
  }

  public function getNextLink() {
    return $this->nextLink;
  }
  
  public function setShelfSpaceAds(ProductsShelfSpaceAds $shelfSpaceAds) {
    $this->shelfSpaceAds = $shelfSpaceAds;
  }

  public function getShelfSpaceAds() {
    return $this->shelfSpaceAds;
  }
  
  public function setStartIndex($startIndex) {
    $this->startIndex = $startIndex;
  }

  public function getStartIndex() {
    return $this->startIndex;
  }
  
  public function setEtag($etag) {
    $this->etag = $etag;
  }

  public function getEtag() {
    return $this->etag;
  }
  
  public function setSelfLink($selfLink) {
    $this->selfLink = $selfLink;
  }

  public function getSelfLink() {
    return $this->selfLink;
  }
  
  public function setRelatedQueries($relatedQueries) {
    $this->relatedQueries = $relatedQueries;
  }

  public function getRelatedQueries() {
    return $this->relatedQueries;
  }
  
  public function setDebug(ShoppingModelDebugJsonV1 $debug) {
    $this->debug = $debug;
  }

  public function getDebug() {
    return $this->debug;
  }
  
  public function setSpelling(ProductsSpelling $spelling) {
    $this->spelling = $spelling;
  }

  public function getSpelling() {
    return $this->spelling;
  }
  
  public function setPreviousLink($previousLink) {
    $this->previousLink = $previousLink;
  }

  public function getPreviousLink() {
    return $this->previousLink;
  }
  
  public function setTotalItems($totalItems) {
    $this->totalItems = $totalItems;
  }

  public function getTotalItems() {
    return $this->totalItems;
  }
  
  public function setId($id) {
    $this->id = $id;
  }

  public function getId() {
    return $this->id;
  }
  
  public function setCategories(ShoppingModelCategoryJsonV1 $categories) {
    $this->categories = $categories;
  }

  public function getCategories() {
    return $this->categories;
  }
  
}


class ShoppingModelProductJsonV1 extends apiModel {

  public $providedId;
  public $description;
  public $gtins;
  public $author;
  public $googleId;
  public $country;
  public $brand;
  public $title;
  public $creationTime;
  public $modificationTime;
  public $language;
  public $gtin;
  public $categories;
  public $images;
  public $attributes;
  public $inventories;
  public $link;
  public $condition;

  public function setProvidedId($providedId) {
    $this->providedId = $providedId;
  }

  public function getProvidedId() {
    return $this->providedId;
  }
  
  public function setDescription($description) {
    $this->description = $description;
  }

  public function getDescription() {
    return $this->description;
  }
  
  public function setGtins($gtins) {
    $this->gtins = $gtins;
  }

  public function getGtins() {
    return $this->gtins;
  }
  
  public function setAuthor(ShoppingModelProductJsonV1Author $author) {
    $this->author = $author;
  }

  public function getAuthor() {
    return $this->author;
  }
  
  public function setGoogleId($googleId) {
    $this->googleId = $googleId;
  }

  public function getGoogleId() {
    return $this->googleId;
  }
  
  public function setCountry($country) {
    $this->country = $country;
  }

  public function getCountry() {
    return $this->country;
  }
  
  public function setBrand($brand) {
    $this->brand = $brand;
  }

  public function getBrand() {
    return $this->brand;
  }
  
  public function setTitle($title) {
    $this->title = $title;
  }

  public function getTitle() {
    return $this->title;
  }
  
  public function setCreationTime($creationTime) {
    $this->creationTime = $creationTime;
  }

  public function getCreationTime() {
    return $this->creationTime;
  }
  
  public function setModificationTime($modificationTime) {
    $this->modificationTime = $modificationTime;
  }

  public function getModificationTime() {
    return $this->modificationTime;
  }
  
  public function setLanguage($language) {
    $this->language = $language;
  }

  public function getLanguage() {
    return $this->language;
  }
  
  public function setGtin($gtin) {
    $this->gtin = $gtin;
  }

  public function getGtin() {
    return $this->gtin;
  }
  
  public function setCategories($categories) {
    $this->categories = $categories;
  }

  public function getCategories() {
    return $this->categories;
  }
  
  public function setImages(ShoppingModelProductJsonV1Images $images) {
    $this->images = $images;
  }

  public function getImages() {
    return $this->images;
  }
  
  public function setAttributes(ShoppingModelProductJsonV1Attributes $attributes) {
    $this->attributes = $attributes;
  }

  public function getAttributes() {
    return $this->attributes;
  }
  
  public function setInventories(ShoppingModelProductJsonV1Inventories $inventories) {
    $this->inventories = $inventories;
  }

  public function getInventories() {
    return $this->inventories;
  }
  
  public function setLink($link) {
    $this->link = $link;
  }

  public function getLink() {
    return $this->link;
  }
  
  public function setCondition($condition) {
    $this->condition = $condition;
  }

  public function getCondition() {
    return $this->condition;
  }
  
}


class ProductsPromotions extends apiModel {

  public $product;
  public $description;
  public $imageLink;
  public $destLink;
  public $customHtml;
  public $link;
  public $customFields;
  public $type;
  public $name;

  public function setProduct(ShoppingModelProductJsonV1 $product) {
    $this->product = $product;
  }

  public function getProduct() {
    return $this->product;
  }
  
  public function setDescription($description) {
    $this->description = $description;
  }

  public function getDescription() {
    return $this->description;
  }
  
  public function setImageLink($imageLink) {
    $this->imageLink = $imageLink;
  }

  public function getImageLink() {
    return $this->imageLink;
  }
  
  public function setDestLink($destLink) {
    $this->destLink = $destLink;
  }

  public function getDestLink() {
    return $this->destLink;
  }
  
  public function setCustomHtml($customHtml) {
    $this->customHtml = $customHtml;
  }

  public function getCustomHtml() {
    return $this->customHtml;
  }
  
  public function setLink($link) {
    $this->link = $link;
  }

  public function getLink() {
    return $this->link;
  }
  
  public function setCustomFields(ProductsPromotionsCustomFields $customFields) {
    $this->customFields = $customFields;
  }

  public function getCustomFields() {
    return $this->customFields;
  }
  
  public function setType($type) {
    $this->type = $type;
  }

  public function getType() {
    return $this->type;
  }
  
  public function setName($name) {
    $this->name = $name;
  }

  public function getName() {
    return $this->name;
  }
  
}


class ShoppingModelProductJsonV1ImagesThumbnails extends apiModel {

  public $content;
  public $width;
  public $link;
  public $height;

  public function setContent($content) {
    $this->content = $content;
  }

  public function getContent() {
    return $this->content;
  }
  
  public function setWidth($width) {
    $this->width = $width;
  }

  public function getWidth() {
    return $this->width;
  }
  
  public function setLink($link) {
    $this->link = $link;
  }

  public function getLink() {
    return $this->link;
  }
  
  public function setHeight($height) {
    $this->height = $height;
  }

  public function getHeight() {
    return $this->height;
  }
  
}


class ProductsPromotionsCustomFields extends apiModel {

  public $name;
  public $value;

  public function setName($name) {
    $this->name = $name;
  }

  public function getName() {
    return $this->name;
  }
  
  public function setValue($value) {
    $this->value = $value;
  }

  public function getValue() {
    return $this->value;
  }
  
}


class ShoppingModelProductJsonV1Author extends apiModel {

  public $aggregatorId;
  public $uri;
  public $email;
  public $name;
  public $accountId;

  public function setAggregatorId($aggregatorId) {
    $this->aggregatorId = $aggregatorId;
  }

  public function getAggregatorId() {
    return $this->aggregatorId;
  }
  
  public function setUri($uri) {
    $this->uri = $uri;
  }

  public function getUri() {
    return $this->uri;
  }
  
  public function setEmail($email) {
    $this->email = $email;
  }

  public function getEmail() {
    return $this->email;
  }
  
  public function setName($name) {
    $this->name = $name;
  }

  public function getName() {
    return $this->name;
  }
  
  public function setAccountId($accountId) {
    $this->accountId = $accountId;
  }

  public function getAccountId() {
    return $this->accountId;
  }
  
}


class ShoppingModelProductJsonV1Attributes extends apiModel {

  public $type;
  public $value;
  public $displayName;
  public $name;
  public $unit;

  public function setType($type) {
    $this->type = $type;
  }

  public function getType() {
    return $this->type;
  }
  
  public function setValue($value) {
    $this->value = $value;
  }

  public function getValue() {
    return $this->value;
  }
  
  public function setDisplayName($displayName) {
    $this->displayName = $displayName;
  }

  public function getDisplayName() {
    return $this->displayName;
  }
  
  public function setName($name) {
    $this->name = $name;
  }

  public function getName() {
    return $this->name;
  }
  
  public function setUnit($unit) {
    $this->unit = $unit;
  }

  public function getUnit() {
    return $this->unit;
  }
  
}

