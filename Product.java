
package com.example;

import javax.annotation.Generated;
import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

@Generated("jsonschema2pojo")
public class Product {

    @SerializedName("id")
    @Expose
    private Integer id;
    @SerializedName("name")
    @Expose
    private String name;
    @SerializedName("category_id")
    @Expose
    private Integer categoryId;
    @SerializedName("photos")
    @Expose
    private String photos;
    @SerializedName("thumbnail_img")
    @Expose
    private String thumbnailImg;
    @SerializedName("unit_price")
    @Expose
    private Integer unitPrice;
    @SerializedName("discount")
    @Expose
    private Integer discount;
    @SerializedName("purchase_price")
    @Expose
    private Object purchasePrice;
    @SerializedName("meta_title")
    @Expose
    private String metaTitle;
    @SerializedName("meta_description")
    @Expose
    private String metaDescription;
    @SerializedName("meta_img")
    @Expose
    private String metaImg;
    @SerializedName("min_qtn")
    @Expose
    private Integer minQtn;
    @SerializedName("published")
    @Expose
    private Integer published;

    public Integer getId() {
        return id;
    }

    public void setId(Integer id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public Integer getCategoryId() {
        return categoryId;
    }

    public void setCategoryId(Integer categoryId) {
        this.categoryId = categoryId;
    }

    public String getPhotos() {
        return photos;
    }

    public void setPhotos(String photos) {
        this.photos = photos;
    }

    public String getThumbnailImg() {
        return thumbnailImg;
    }

    public void setThumbnailImg(String thumbnailImg) {
        this.thumbnailImg = thumbnailImg;
    }

    public Integer getUnitPrice() {
        return unitPrice;
    }

    public void setUnitPrice(Integer unitPrice) {
        this.unitPrice = unitPrice;
    }

    public Integer getDiscount() {
        return discount;
    }

    public void setDiscount(Integer discount) {
        this.discount = discount;
    }

    public Object getPurchasePrice() {
        return purchasePrice;
    }

    public void setPurchasePrice(Object purchasePrice) {
        this.purchasePrice = purchasePrice;
    }

    public String getMetaTitle() {
        return metaTitle;
    }

    public void setMetaTitle(String metaTitle) {
        this.metaTitle = metaTitle;
    }

    public String getMetaDescription() {
        return metaDescription;
    }

    public void setMetaDescription(String metaDescription) {
        this.metaDescription = metaDescription;
    }

    public String getMetaImg() {
        return metaImg;
    }

    public void setMetaImg(String metaImg) {
        this.metaImg = metaImg;
    }

    public Integer getMinQtn() {
        return minQtn;
    }

    public void setMinQtn(Integer minQtn) {
        this.minQtn = minQtn;
    }

    public Integer getPublished() {
        return published;
    }

    public void setPublished(Integer published) {
        this.published = published;
    }

}
