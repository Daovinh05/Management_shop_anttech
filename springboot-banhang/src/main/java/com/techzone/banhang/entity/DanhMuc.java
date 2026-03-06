package com.techzone.banhang.entity;

import jakarta.persistence.*;
import lombok.*;
import org.hibernate.annotations.CreationTimestamp;

import java.time.LocalDateTime;

@Entity
@Table(name = "danh_muc")
@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class DanhMuc {

    @Id
    @Column(name = "ma_danh_muc", length = 20)
    private String maDanhMuc;

    @Column(name = "ten_danh_muc", length = 255, nullable = false)
    private String tenDanhMuc;

    @Column(name = "ngay_tao")
    @CreationTimestamp
    private LocalDateTime ngayTao;
}
