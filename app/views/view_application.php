<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Details - Admin Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
            border: 2px solid #f59e0b;
        }
        .status-approved {
            background-color: #d1fae5;
            color: #065f46;
            border: 2px solid #10b981;
        }
        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
            border: 2px solid #ef4444;
        }
        .detail-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 4px 16px 0 rgba(0, 0, 0, 0.1), 0 1.5px 0 #000;
            outline: 1px solid #000;
            margin-bottom: 2rem;
        }
        .student-info {
            background: #a31d1d;
            color: white;
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px 0 rgba(0, 0, 0, 0.1), 0 1.5px 0 #000;
            outline: 1px solid #000;
        }
        .document-card {
            background: rgba(248, 249, 250, 0.9);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        .document-card:hover {
            border-color: #a31d1d;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .action-btn {
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            outline: 1px solid #000;
            box-shadow: 0 4px 0 #000;
        }
        .action-btn:hover {
            transform: translateY(-2px);
        }
        .btn-approve {
            background: #198754;
            border: none;
            color: white;
        }
        .btn-reject {
            background: #dc3545;
            border: none;
            color: white;
        }
        .description-box {
            background: #f8f9fa;
            border-radius: 0.75rem;
            padding: 1.5rem;
            border-left: 4px solid #a31d1d;
        }
        .remarks-box {
            background: #fff7ed;
            border-radius: 0.75rem;
            padding: 1.5rem;
            border-left: 4px solid #ffc107;
        }
        .document-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .back-btn {
            background: #a31d1d;
            border: none;
            color: white;
            border-radius: 0.75rem;
            padding: 0.75rem 1.25rem;
            outline: 1px solid #000;
            box-shadow: 0 4px 0 #000;
        }
        .back-btn:hover {
            color: white;
            background: #8a1818;
            transform: translateY(-2px);
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-image: radial-gradient(circle at 1px 1px, #e2e8f0 1px, transparent 0), linear-gradient(to right, rgba(255,255,255,0.2), rgba(255,255,255,0.2));
            background-size: 24px 24px;
            background-color: #f8f9fa;
        }
        .table thead th {
            color: #a31d1d;
            font-weight: 700;
            border-bottom-color: #a31d1d;
        }
        .form-control:focus, .form-select:focus {
            border-color: #a31d1d;
            box-shadow: 0 0 0 0.25rem rgba(163, 29, 29, 0.15);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <?php if (!$application): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">
                            <i class="fas fa-user-check me-2"></i>
                            Excuse a Student
                        </h2>
                        <a href="<?= ROOT ?>student_application" class="btn back-btn">
                            <i class="fas fa-arrow-left me-2"></i>Back to Applications
                        </a>
                    </div>
                </div>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="detail-card">
                <h4 class="mb-3"><i class="fas fa-calendar-check me-2"></i>Choose an Event</h4>
                <form method="GET" action="<?= ROOT ?>view_application" class="row g-3">
                    <div class="col-md-8">
                        <label for="event_id" class="form-label">Event</label>
                        <select name="event_id" id="event_id" class="form-select" required>
                            <option value="">Select an event</option>
                            <?php foreach ($events as $event): ?>
                                <option value="<?= htmlspecialchars($event['atten_id']) ?>" <?= (string) $selectedEventId === (string) $event['atten_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($event['event_name']) ?> - <?= htmlspecialchars($event['date_created'] ?? '') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-arrow-right me-2"></i>Load Students
                        </button>
                    </div>
                </form>
            </div>

            <?php if ($selectedEventId): ?>
                <div class="detail-card">
                    <h4 class="mb-3"><i class="fas fa-search me-2"></i>Find Student</h4>
                    <form method="GET" action="<?= ROOT ?>view_application" class="row g-3">
                        <input type="hidden" name="event_id" value="<?= htmlspecialchars($selectedEventId) ?>">
                        <div class="col-md-9">
                            <label for="search" class="form-label">Student name or ID</label>
                            <input type="search" name="search" id="search" class="form-control"
                                   value="<?= htmlspecialchars($searchQuery) ?>"
                                   placeholder="Search by student name or ID">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-dark w-100">
                                <i class="fas fa-search me-2"></i>Search
                            </button>
                        </div>
                    </form>
                </div>

                <div class="detail-card">
                    <h4 class="mb-3"><i class="fas fa-users me-2"></i>Students</h4>
                    <?php if (empty($students)): ?>
                        <p class="text-muted mb-0">No students matched your search.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Student ID</th>
                                        <th>Excuse Status</th>
                                        <th style="min-width: 360px;">Excuse Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $student): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($student['name']) ?></strong><br>
                                                <small class="text-muted"><?= htmlspecialchars($student['program'] ?? '') ?> <?= htmlspecialchars($student['acad_year'] ?? '') ?></small>
                                            </td>
                                            <td><?= htmlspecialchars($student['student_id']) ?></td>
                                            <td>
                                                <?php if ($student['application_id']): ?>
                                                    <?php $statusLabels = ['Pending', 'Approved', 'Rejected']; ?>
                                                    <span class="badge bg-<?= $student['application_status'] == 1 ? 'success' : ($student['application_status'] == 2 ? 'danger' : 'warning') ?>">
                                                        <?= $statusLabels[(int) $student['application_status']] ?? 'Unknown' ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">No application</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($student['application_id']): ?>
                                                    <a href="<?= ROOT ?>view_application?id=<?= htmlspecialchars($student['application_id']) ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye me-1"></i>View application
                                                    </a>
                                                <?php else: ?>
                                                    <form method="POST" action="<?= ROOT ?>view_application">
                                                        <input type="hidden" name="action" value="create_excuse">
                                                        <input type="hidden" name="atten_id" value="<?= htmlspecialchars($selectedEventId) ?>">
                                                        <input type="hidden" name="student_id" value="<?= htmlspecialchars($student['student_id']) ?>">
                                                        <textarea name="application_description" class="form-control form-control-sm mb-2" rows="2" required placeholder="Reason for excuse"></textarea>
                                                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-user-check me-1"></i>Excuse Student</button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        Application Details
                    </h2>
                    <a href="<?= ROOT ?>student_application" class="btn back-btn">
                        <i class="fas fa-arrow-left me-2"></i>Back to Applications
                    </a>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Student Information -->
        <div class="student-info">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="mb-2">
                        <i class="fas fa-user-circle me-2"></i>
                        <?= htmlspecialchars($application['name']) ?>
                    </h3>
                    <p class="mb-1">
                        <i class="fas fa-graduation-cap me-2"></i>
                        <?= htmlspecialchars($application['program']) ?> - <?= htmlspecialchars($application['acad_year']) ?>
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-calendar me-2"></i>
                        Application submitted on <?= date('F d, Y \a\t g:i A', strtotime($application['date_submitted'])) ?>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <?php
                    $statusClass = '';
                    $statusText = '';
                    $statusIcon = '';
                    
                    switch ($application['application_status']) {
                        case 0:
                            $statusClass = 'status-pending';
                            $statusText = 'Pending Review';
                            $statusIcon = 'fas fa-clock';
                            break;
                        case 1:
                            $statusClass = 'status-approved';
                            $statusText = 'Approved';
                            $statusIcon = 'fas fa-check';
                            break;
                        case 2:
                            $statusClass = 'status-rejected';
                            $statusText = 'Rejected';
                            $statusIcon = 'fas fa-times';
                            break;
                    }
                    ?>
                    <span class="status-badge <?= $statusClass ?>">
                        <i class="<?= $statusIcon ?> me-2"></i>
                        <?= $statusText ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Application Details -->
            <div class="col-md-8">
                <div class="detail-card">
                    <h4 class="mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Application Information
                    </h4>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Event Name</h6>
                            <p class="fw-bold"><?= htmlspecialchars($application['event_name']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Event Date</h6>
                            <p class="fw-bold"><?= date('F d, Y', strtotime($application['event_date'])) ?></p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Application Description</h6>
                        <div class="description-box">
                            <?= nl2br(htmlspecialchars($application['application_description'])) ?>
                        </div>
                    </div>

                    <?php if ($application['admin_remarks']): ?>
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Admin Remarks</h6>
                            <div class="remarks-box">
                                <?= nl2br(htmlspecialchars($application['admin_remarks'])) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Action Buttons (only show for pending applications) -->
                    <?php if ($application['application_status'] == 0): ?>
                        <div class="mt-4">
                            <h5 class="mb-3">Take Action</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="button" class="btn action-btn btn-approve w-100" data-bs-toggle="modal" data-bs-target="#approveModal">
                                        <i class="fas fa-check me-2"></i>Approve Application
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn action-btn btn-reject w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                        <i class="fas fa-times me-2"></i>Reject Application
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Documents Section -->
            <div class="col-md-4">
                <div class="detail-card">
                    <h4 class="mb-3">
                        <i class="fas fa-paperclip me-2"></i>
                        Supporting Documents
                    </h4>

                    <?php if ($document1Info || $document2Info): ?>
                        <?php if ($document1Info): ?>
                            <div class="document-card">
                                <div class="text-center mb-3">
                                    <i class="<?= $document1Info['icon'] ?> document-icon text-primary"></i>
                                </div>
                                <h6 class="mb-2">Document 1</h6>
                                <p class="mb-2">
                                    <small class="text-muted">
                                        Type: <?= strtoupper($document1Info['extension']) ?> | 
                                        Size: <?= $document1Info['size_formatted'] ?>
                                    </small>
                                </p>
                                <div class="d-flex gap-2">
                                    <a href="<?= ROOT ?>student_application?action=viewDocument&id=<?= $application['id'] ?>&doc=1" 
                                       class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                    <a href="<?= ROOT ?>student_application?action=downloadDocument&id=<?= $application['id'] ?>&doc=1" 
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-download me-1"></i>Download
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($document2Info): ?>
                            <div class="document-card">
                                <div class="text-center mb-3">
                                    <i class="<?= $document2Info['icon'] ?> document-icon text-primary"></i>
                                </div>
                                <h6 class="mb-2">Document 2</h6>
                                <p class="mb-2">
                                    <small class="text-muted">
                                        Type: <?= strtoupper($document2Info['extension']) ?> | 
                                        Size: <?= $document2Info['size_formatted'] ?>
                                    </small>
                                </p>
                                <div class="d-flex gap-2">
                                    <a href="<?= ROOT ?>student_application?action=viewDocument&id=<?= $application['id'] ?>&doc=2" 
                                       class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                    <a href="<?= ROOT ?>student_application?action=downloadDocument&id=<?= $application['id'] ?>&doc=2" 
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-download me-1"></i>Download
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-file-alt" style="font-size: 3rem; opacity: 0.5;"></i>
                            <p class="mt-3">No supporting documents attached</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
        <?php endif; ?>

    <!-- Approve Modal -->
    <?php if ($application): ?>
    <div class="modal fade" id="approveModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Approve Application
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <p>Are you sure you want to approve this application?</p>
                        <div class="mb-3">
                            <label class="form-label">Remarks (Optional)</label>
                            <textarea class="form-control" name="remarks" rows="3" 
                                      placeholder="Add any additional remarks..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="status" value="1" class="btn btn-success">
                            <i class="fas fa-check me-2"></i>Approve
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-times-circle text-danger me-2"></i>
                        Reject Application
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <p>Are you sure you want to reject this application?</p>
                        <div class="mb-3">
                            <label class="form-label">Remarks <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="remarks" rows="3" required
                                      placeholder="Please provide a reason for rejection..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="status" value="2" class="btn btn-danger">
                            <i class="fas fa-times me-2"></i>Reject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
