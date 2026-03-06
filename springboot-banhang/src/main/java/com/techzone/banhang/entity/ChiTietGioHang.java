package com.techzone.banhang.entity;

import jakarta.persistence.*;
import lombok.*;

@Entity
@Table(name = "chi_tiet_gio_hang")
@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
@IdClass(ChiTietGioHangId.class)
public class ChiTietGioHang {

    @Id
    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "ma_gio_hang", nullable = false)
    private GioHang gioHang;

    @Id
    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "ma_bien_the", nullable = false)
    private BienThe bienThe;

    @Column(name = "so_luong")
    private Integer soLuong = 1;
}
