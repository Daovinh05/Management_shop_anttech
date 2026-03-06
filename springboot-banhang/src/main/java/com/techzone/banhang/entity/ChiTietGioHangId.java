package com.techzone.banhang.entity;

import lombok.*;

import java.io.Serializable;
import java.util.Objects;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class ChiTietGioHangId implements Serializable {

    private String gioHang;
    private String bienThe;

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        ChiTietGioHangId that = (ChiTietGioHangId) o;
        return Objects.equals(gioHang, that.gioHang) &&
               Objects.equals(bienThe, that.bienThe);
    }

    @Override
    public int hashCode() {
        return Objects.hash(gioHang, bienThe);
    }
}
