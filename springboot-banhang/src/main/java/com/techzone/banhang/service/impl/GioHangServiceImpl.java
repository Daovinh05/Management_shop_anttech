package com.techzone.banhang.service.impl;

import com.techzone.banhang.entity.GioHang;
import com.techzone.banhang.entity.User;
import com.techzone.banhang.repository.GioHangRepository;
import com.techzone.banhang.repository.UserRepository;
import com.techzone.banhang.service.GioHangService;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Optional;

@Service
@RequiredArgsConstructor
@Transactional
public class GioHangServiceImpl implements GioHangService {

    private final GioHangRepository gioHangRepository;
    private final UserRepository userRepository;

    @Override
    @Transactional(readOnly = true)
    public List<GioHang> getAllGioHang() {
        return gioHangRepository.findAll();
    }

    @Override
    @Transactional(readOnly = true)
    public Optional<GioHang> getGioHangById(String id) {
        return gioHangRepository.findById(id);
    }

    @Override
    @Transactional(readOnly = true)
    public Optional<GioHang> getGioHangByUser(String maUser) {
        User user = userRepository.findById(maUser).orElseThrow();
        return gioHangRepository.findByUser(user);
    }

    @Override
    public GioHang createGioHang(GioHang gioHang) {
        return gioHangRepository.save(gioHang);
    }

    @Override
    public GioHang updateGioHang(GioHang gioHang) {
        return gioHangRepository.save(gioHang);
    }

    @Override
    public void deleteGioHang(String id) {
        gioHangRepository.deleteById(id);
    }
}
