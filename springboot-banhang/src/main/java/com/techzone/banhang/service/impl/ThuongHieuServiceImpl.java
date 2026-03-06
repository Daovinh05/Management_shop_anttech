package com.techzone.banhang.service.impl;

import com.techzone.banhang.entity.ThuongHieu;
import com.techzone.banhang.repository.ThuongHieuRepository;
import com.techzone.banhang.service.ThuongHieuService;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;

@Service
@RequiredArgsConstructor
@Transactional
public class ThuongHieuServiceImpl implements ThuongHieuService {

    private final ThuongHieuRepository thuongHieuRepository;

    @Override
    @Transactional(readOnly = true)
    public List<ThuongHieu> getAllThuongHieu() {
        return thuongHieuRepository.findAll();
    }

    @Override
    @Transactional(readOnly = true)
    public ThuongHieu getThuongHieuById(String id) {
        return thuongHieuRepository.findById(id).orElseThrow();
    }

    @Override
    public ThuongHieu createThuongHieu(ThuongHieu thuongHieu) {
        return thuongHieuRepository.save(thuongHieu);
    }

    @Override
    public ThuongHieu updateThuongHieu(ThuongHieu thuongHieu) {
        return thuongHieuRepository.save(thuongHieu);
    }

    @Override
    public void deleteThuongHieu(String id) {
        thuongHieuRepository.deleteById(id);
    }
}
