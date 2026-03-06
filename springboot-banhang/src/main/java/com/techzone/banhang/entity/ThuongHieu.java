package com.techzone.banhang.entity;

import jakarta.persistence.*;
import lombok.*;
import org.hibernate.annotations.CreationTimestamp;

import java.time.LocalDateTime;

@Entity
@Table(name = "thuong_hieu")
@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class ThuongHieu {

    @Id
    @Column(name = "ma_thuong_hieu", length = 20)
    private String maThuongHieu;

    @Column(name = "ten_thuong_hieu", length = 255, nullable = false)
    private String tenThuongHieu;

    @Column(name = "ngay_tao")
    @CreationTimestamp
    private LocalDateTime ngayTao;
}
