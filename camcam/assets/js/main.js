/**
 * Modern Luxury Gold & Silver IT Student Portfolio JavaScript
 * Student Developer: John Carlo Camcam
 * Features:
 *   - Sticky Navbar Scroll State
 *   - Mobile Drawer Navigation
 *   - Project Category Filtering
 *   - Project Detail Modal Popups
 *   - Interactive Student Resume (CV) Modal
 *   - Multi-Tab SQL Code Studio Switcher
 *   - 1-Click Copy Active SQL Code with Feedback
 *   - 1-Click Copy Student Email
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Sticky Navbar Scroll State
  const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    if (window.scrollY > 30) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });

  // Dynamic Scroll Spy for Navbar Links
  const trackedSections = document.querySelectorAll('section[id]');
  const trackedNavLinks = document.querySelectorAll('.nav-links .nav-link');
  window.addEventListener('scroll', () => {
    let currentId = '';
    trackedSections.forEach(sec => {
      const secTop = sec.offsetTop - 140;
      if (window.scrollY >= secTop) {
        currentId = sec.getAttribute('id');
      }
    });
    if (currentId) {
      trackedNavLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${currentId}`) {
          link.classList.add('active');
        }
      });
    }
  });

  // 2. Mobile Navigation Toggle
  const navToggle = document.getElementById('navToggle');
  const navLinks = document.getElementById('navLinks');
  if (navToggle && navLinks) {
    navToggle.addEventListener('click', () => {
      navLinks.classList.toggle('active');
    });

    navLinks.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', () => {
        navLinks.classList.remove('active');
      });
    });
  }

  // 3. Project Category Filtering
  const filterPills = document.querySelectorAll('.filter-pill');
  const projectCards = document.querySelectorAll('.project-card');

  filterPills.forEach(pill => {
    pill.addEventListener('click', () => {
      filterPills.forEach(p => p.classList.remove('active'));
      pill.classList.add('active');

      const filterValue = pill.getAttribute('data-filter');

      projectCards.forEach(card => {
        if (filterValue === 'all') {
          card.style.display = 'flex';
          card.style.opacity = '1';
        } else {
          const category = card.getAttribute('data-category') || '';
          if (category.toLowerCase().includes(filterValue.toLowerCase())) {
            card.style.display = 'flex';
            card.style.opacity = '1';
          } else {
            card.style.display = 'none';
          }
        }
      });
    });
  });

  // 4. Project Detail Modal Viewer
  const modalOverlay = document.getElementById('projectModal');
  const modalCloseBtn = document.getElementById('modalCloseBtn');
  const modalImage = document.getElementById('modalImage');
  const modalTitle = document.getElementById('modalTitle');
  const modalCategory = document.getElementById('modalCategory');
  const modalDesc = document.getElementById('modalDesc');
  const modalFeatures = document.getElementById('modalFeatures');
  const modalTechStack = document.getElementById('modalTechStack');
  const modalGithubLink = document.getElementById('modalGithubLink');
  const modalDemoLink = document.getElementById('modalDemoLink');

  document.querySelectorAll('.open-modal-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const card = btn.closest('.project-card');
      if (!card) return;

      const title = card.getAttribute('data-title');
      const category = card.getAttribute('data-category');
      const image = card.getAttribute('data-image');
      const fullDesc = card.getAttribute('data-full-desc');
      const featuresRaw = card.getAttribute('data-features') || '';
      const tech = card.getAttribute('data-tech') || '';
      const github = card.getAttribute('data-github') || '#';
      const demo = card.getAttribute('data-demo') || '#';

      if (modalTitle) modalTitle.textContent = title;
      if (modalCategory) modalCategory.textContent = category;
      if (modalImage) {
        modalImage.src = image;
        modalImage.alt = title;
      }
      if (modalDesc) modalDesc.textContent = fullDesc;

      // Populate features checklist
      if (modalFeatures) {
        modalFeatures.innerHTML = '';
        featuresRaw.split(';').forEach(feat => {
          if (feat.trim()) {
            const li = document.createElement('li');
            li.style.position = 'relative';
            li.style.paddingLeft = '1.5rem';
            li.style.fontSize = '0.98rem';
            li.style.color = 'var(--silver-primary)';
            li.innerHTML = '<span style="position: absolute; left: 0; color: var(--gold-primary);">✦</span> ' + feat.trim();
            modalFeatures.appendChild(li);
          }
        });
      }

      // Populate tech stack badges
      if (modalTechStack) {
        modalTechStack.innerHTML = '';
        tech.split(',').forEach(t => {
          if (t.trim()) {
            const span = document.createElement('span');
            span.className = 'tech-pill primary';
            span.textContent = t.trim();
            modalTechStack.appendChild(span);
          }
        });
      }

      if (modalDemoLink) modalDemoLink.href = demo;
      if (modalGithubLink) modalGithubLink.href = github;

      modalOverlay.classList.add('active');
      document.body.style.overflow = 'hidden';
    });
  });

  const closeProjectModal = () => {
    if (modalOverlay) {
      modalOverlay.classList.remove('active');
      document.body.style.overflow = 'auto';
    }
  };

  if (modalCloseBtn) modalCloseBtn.addEventListener('click', closeProjectModal);
  if (modalOverlay) {
    modalOverlay.addEventListener('click', (e) => {
      if (e.target === modalOverlay) closeProjectModal();
    });
  }

  // 5. Student Resume (CV) Pop-Up Modal
  const resumeModal = document.getElementById('resumeModal');
  const resumeCloseBtn = document.getElementById('resumeCloseBtn');
  const studentResumeImg = document.getElementById('studentResumeImg');
  const resumeZoomBtn = document.getElementById('resumeZoomBtn');

  const openResumeModal = (e) => {
    if (typeof window.openResumeModal === 'function') {
      window.openResumeModal(e);
      return;
    }
    if (e && e.preventDefault) e.preventDefault();
    if (resumeModal) {
      resumeModal.style.display = 'flex';
      resumeModal.style.opacity = '1';
      resumeModal.style.visibility = 'visible';
      resumeModal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }
  };

  const closeResumeModal = (e) => {
    if (typeof window.closeResumeModal === 'function') {
      window.closeResumeModal(e);
      return;
    }
    if (e && e.preventDefault) e.preventDefault();
    if (resumeModal) {
      resumeModal.classList.remove('active');
      resumeModal.style.display = 'none';
      resumeModal.style.opacity = '0';
      resumeModal.style.visibility = 'hidden';
      document.body.style.overflow = 'auto';
      if (studentResumeImg) studentResumeImg.classList.remove('zoomed');
      if (resumeZoomBtn) resumeZoomBtn.innerHTML = '<span>🔍 Zoom</span>';
    }
  };

  const toggleResumeZoom = () => {
    if (studentResumeImg) {
      studentResumeImg.classList.toggle('zoomed');
      if (resumeZoomBtn) {
        resumeZoomBtn.innerHTML = studentResumeImg.classList.contains('zoomed')
          ? '<span>🔍 Zoom Out</span>'
          : '<span>🔍 Zoom In</span>';
      }
    }
  };

  const resumeTriggers = document.querySelectorAll('[data-action="open-resume"], #navResumeBtn, #heroCvBtn, #cardResumeTrigger, #aboutResumeBtn, #interviewResumeBtn, #contactModalResumeBtn, #footerResumeBtn');

  resumeTriggers.forEach(btn => {
    btn.addEventListener('click', openResumeModal);
  });

  if (resumeCloseBtn) resumeCloseBtn.addEventListener('click', closeResumeModal);
  if (resumeZoomBtn) resumeZoomBtn.addEventListener('click', toggleResumeZoom);
  if (studentResumeImg) studentResumeImg.addEventListener('click', toggleResumeZoom);

  if (resumeModal) {
    resumeModal.addEventListener('click', (e) => {
      if (e.target === resumeModal) closeResumeModal();
    });
  }

  // Close on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && resumeModal && resumeModal.classList.contains('active')) {
      closeResumeModal();
    }
  });

  // 5b. Login Modal Handling
  const loginModal = document.getElementById('loginModal');
  const loginCloseBtn = document.getElementById('loginCloseBtn');
  const loginTriggers = [
    document.getElementById('navLoginBtn'),
    document.getElementById('footerLoginBtn')
  ];

  const openLoginModal = (e) => {
    if (e) e.preventDefault();
    if (loginModal) {
      loginModal.classList.add('active');
      document.body.style.overflow = 'hidden';
      const uInput = document.getElementById('login_username');
      if (uInput) setTimeout(() => uInput.focus(), 150);
    }
  };

  const closeLoginModal = () => {
    if (loginModal) {
      loginModal.classList.remove('active');
      document.body.style.overflow = 'auto';
    }
  };

  loginTriggers.forEach(btn => {
    if (btn) btn.addEventListener('click', openLoginModal);
  });
  if (loginCloseBtn) loginCloseBtn.addEventListener('click', closeLoginModal);
  if (loginModal) {
    loginModal.addEventListener('click', (e) => {
      if (e.target === loginModal) closeLoginModal();
    });
  }

  // 5c. Admin Messages Modal Handling
  const adminModal = document.getElementById('adminModal');
  const adminCloseBtn = document.getElementById('adminCloseBtn');
  const adminTriggers = [
    document.getElementById('navAdminBtn'),
    document.getElementById('footerAdminBtn')
  ];

  const openAdminModal = (e) => {
    if (e) e.preventDefault();
    if (adminModal) {
      adminModal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }
  };

  const closeAdminModal = () => {
    if (adminModal) {
      adminModal.classList.remove('active');
      document.body.style.overflow = 'auto';
    }
  };

  adminTriggers.forEach(btn => {
    if (btn) btn.addEventListener('click', openAdminModal);
  });
  if (adminCloseBtn) adminCloseBtn.addEventListener('click', closeAdminModal);
  if (adminModal) {
    adminModal.addEventListener('click', (e) => {
      if (e.target === adminModal) closeAdminModal();
    });
  }

  // Global ESC key to close any active modal
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeProjectModal();
      closeResumeModal();
      closeLoginModal();
      closeAdminModal();
    }
  });

  // 6. Multi-Tab SQL Code Studio Switcher
  const ideTabBtns = document.querySelectorAll('.ide-tab-btn');
  const ideCodePanes = document.querySelectorAll('.ide-code-pane');

  ideTabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const targetTabId = btn.getAttribute('data-tab');

      ideTabBtns.forEach(b => b.classList.remove('active'));
      ideCodePanes.forEach(pane => pane.classList.remove('active'));

      btn.classList.add('active');
      const targetPane = document.getElementById(targetTabId);
      if (targetPane) {
        targetPane.classList.add('active');
      }
    });
  });

  // 7. Copy Active SQL Code Button
  const copySqlBtn = document.getElementById('copySqlBtn');
  if (copySqlBtn) {
    copySqlBtn.addEventListener('click', () => {
      const activePane = document.querySelector('.ide-code-pane.active code');
      if (activePane) {
        const sqlText = activePane.innerText;
        navigator.clipboard.writeText(sqlText).then(() => {
          const originalText = copySqlBtn.innerHTML;
          copySqlBtn.innerHTML = '✓ Copied Active SQL';
          copySqlBtn.classList.add('btn-gold');
          copySqlBtn.classList.remove('btn-silver');
          setTimeout(() => {
            copySqlBtn.innerHTML = originalText;
            copySqlBtn.classList.remove('btn-gold');
            copySqlBtn.classList.add('btn-silver');
          }, 2200);
        });
      }
    });
  }

  // 8. Copy Student Email Action
  const cardEmailCopyBtn = document.getElementById('cardEmailCopyBtn');
  if (cardEmailCopyBtn) {
    cardEmailCopyBtn.addEventListener('click', () => {
      const email = cardEmailCopyBtn.getAttribute('data-email');
      if (email) {
        navigator.clipboard.writeText(email).then(() => {
          const originalText = cardEmailCopyBtn.innerHTML;
          cardEmailCopyBtn.innerHTML = '✓ Copied Email!';
          cardEmailCopyBtn.style.borderColor = 'var(--gold-primary)';
          cardEmailCopyBtn.style.color = 'var(--gold-light)';
          setTimeout(() => {
            cardEmailCopyBtn.innerHTML = originalText;
            cardEmailCopyBtn.style.borderColor = '';
            cardEmailCopyBtn.style.color = '';
          }, 2000);
  // 9. Mock Interview Video Player Controls
  const mockVideo = document.getElementById('mockInterviewVideo');
  const togglePlayBtn = document.getElementById('togglePlayVideoBtn');
  const fullscreenBtn = document.getElementById('fullscreenVideoBtn');

  if (mockVideo && togglePlayBtn) {
    togglePlayBtn.addEventListener('click', () => {
      if (mockVideo.paused || mockVideo.ended) {
        mockVideo.play();
        togglePlayBtn.innerHTML = '<span>⏸ Pause Video</span>';
        togglePlayBtn.classList.add('active');
      } else {
        mockVideo.pause();
        togglePlayBtn.innerHTML = '<span>▶ Play Video</span>';
        togglePlayBtn.classList.remove('active');
      }
    });

    mockVideo.addEventListener('play', () => {
      togglePlayBtn.innerHTML = '<span>⏸ Pause Video</span>';
      togglePlayBtn.classList.add('active');
    });

    mockVideo.addEventListener('pause', () => {
      togglePlayBtn.innerHTML = '<span>▶ Play Video</span>';
      togglePlayBtn.classList.remove('active');
    });

    mockVideo.addEventListener('ended', () => {
      togglePlayBtn.innerHTML = '<span>↺ Replay Video</span>';
      togglePlayBtn.classList.remove('active');
    });
  }

  if (mockVideo && fullscreenBtn) {
    fullscreenBtn.addEventListener('click', () => {
      if (mockVideo.requestFullscreen) {
        mockVideo.requestFullscreen();
      } else if (mockVideo.webkitRequestFullscreen) {
        mockVideo.webkitRequestFullscreen();
      } else if (mockVideo.msRequestFullscreen) {
        mockVideo.msRequestFullscreen();
      }
    });
  }
});
