package com.techzone.banhang.entity;

import jakarta.persistence.*;
import lombok.*;

import java.util.List;

@Entity
@Table(name = "gio_hang")
@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class GioHang {

    @Id
    @Column(name = "ma_gio_hang", length = 20)
    private String maGioHang;

    @OneToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "ma_user", nullable = false)
    private User user;

    @OneToMany(mappedBy = "gioHang", cascade = CascadeType.ALL, fetch = FetchType.LAZY)
    private List<ChiTietGioHang> chiTietGioHangList;
}
