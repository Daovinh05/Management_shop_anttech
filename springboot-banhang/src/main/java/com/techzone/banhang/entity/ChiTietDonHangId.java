package com.techzone.banhang.entity;

import lombok.*;

import java.io.Serializable;
import java.util.Objects;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class ChiTietDonHangId implements Serializable {

    private String maCtdh;
    private String donHang;
    private String bienThe;

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        ChiTietDonHangId that = (ChiTietDonHangId) o;
        return Objects.equals(maCtdh, that.maCtdh) &&
               Objects.equals(donHang, that.donHang) &&
               Objects.equals(bienThe, that.bienThe);
    }

    @Override
    public int hashCode() {
        return Objects.hash(maCtdh, donHang, bienThe);
    }
}
