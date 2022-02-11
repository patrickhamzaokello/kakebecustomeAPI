
package com.example;

import javax.annotation.Generated;
import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

@Generated("jsonschema2pojo")
public class SliderBanner {

    @SerializedName("id")
    @Expose
    private Integer id;
    @SerializedName("link")
    @Expose
    private Integer link;
    @SerializedName("filePath")
    @Expose
    private String filePath;

    public Integer getId() {
        return id;
    }

    public void setId(Integer id) {
        this.id = id;
    }

    public Integer getLink() {
        return link;
    }

    public void setLink(Integer link) {
        this.link = link;
    }

    public String getFilePath() {
        return filePath;
    }

    public void setFilePath(String filePath) {
        this.filePath = filePath;
    }

}
