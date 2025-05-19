<!DOCTYPE html>
<html>

<head>
    <title>Nonconformity Report & Corrective/Preventive Action Request</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
            /* Untuk lebar kolom yang tetap */
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            vertical-align: top;
        }

        th {
            text-align: left;
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .logo-container {
            text-align: center;
            padding: 10px 0;
        }

        .logo {
            max-width: 100px;
        }

        .table-header {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        /* Style untuk textarea */
        textarea {
            width: 100%;
            height: 80px;
            /* Sesuaikan tinggi yang Anda inginkan */
            box-sizing: border-box;
            /* Agar padding tidak mempengaruhi lebar total */
        }

        /* Style untuk checkbox dan label */
        .checkbox-container {
            display: inline-block;
            margin-right: 10px;
            vertical-align: middle;
            /* rata tengah dengan label */
        }

        .checkbox-container input[type="checkbox"],
        .checkbox-container input[type="radio"] {
            margin-right: 5px;
            vertical-align: middle;
        }

        /* Style untuk bagian Verification */
        .verification-section {
            display: flex;
            align-items: center;
            /* rata tengah vertikal */
        }

        .verification-section>div {
            margin-right: 20px;
            /* Spasi antar div */
        }
    </style>
</head>

<body>

    <table style="table-layout: fixed;">
        <tr>
            <td style="width: 20%;" class="logo-container">
                <img src="{{ asset('assets/img/logodus.png') }}" alt="Logo" class="logo">
            </td>
            <td style="width: 60%; text-align: center;">
                <h2>Nonconformity Report</h2>
                <h2>And Corrective/Preventive Action Request</h2>
            </td>
            <td style="width: 20%; text-align: right;">
                <p>DOCUMENT</p>
                <p>No: FRM-HSE-019.1 Rev.: 01</p>
                <p>Tgl: {{ $ncr->tanggal_audit }} Hal.: 1 of 1</p>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width: 25%;">NCR-CPAR No.: {{ $ncr->id }}</td>
            <td style="width: 25%;">Audit Reference No.: {{ $ncr->element_referensi_ncr }}</td>
            <td style="width: 25%;">Date of Audit: {{ $ncr->tanggal_audit }}</td>
            <td style="width: 25%;">Element: {{ $ncr->element_referensi_ncr }}</td>
        </tr>
        <tr>
            <td>Department Audited: {{ $ncr->perusahaan }}-{{ $ncr->nama_bagian }}</td>
            <td>Lead Auditor: {{ $ncr->nama_hs_officer_2 }}</td>
            <td>Auditee: {{ $ncr->nama_auditee }}</td>
            <td>Auditor: {{ $ncr->nama_hs_officer_1 }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="4">Non conformity categorized as:</td>
        </tr>
        <tr>
            <td colspan="4">
                <div class="checkbox-container">
                    <input type="checkbox" id="system_documentation" name="kategori_ketidaksesuaian[]" value="System Documentation" {{ in_array('System Documentation', $ncr->kategori_ketidaksesuaian) ? 'checked' : '' }}>
                    <label for="system_documentation">System Documentation</label>
                </div>
                <div class="checkbox-container">
                    <input type="checkbox" id="implementation" name="kategori_ketidaksesuaian[]" value="Implementation/Practices" {{ in_array('Implementation/Practices', $ncr->kategori_ketidaksesuaian) ? 'checked' : '' }}>
                    <label for="implementation">Implementation/Practices</label>
                </div>
                <div class="checkbox-container">
                    <input type="checkbox" id="review_analysis" name="kategori_ketidaksesuaian[]" value="Review/Analysis" {{ in_array('Review/Analysis', $ncr->kategori_ketidaksesuaian) ? 'checked' : '' }}>
                    <label for="review_analysis">Review/Analysis</label>
                </div>
                <div class="checkbox-container">
                    <input type="checkbox" id="improvement_action" name="kategori_ketidaksesuaian[]" value="Improvement Action" {{ in_array('Improvement Action', $ncr->kategori_ketidaksesuaian) ? 'checked' : '' }}>
                    <label for="improvement_action">Improvement action</label>
                </div>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="2">Identified of Non-Conformity/ Potential Non-Conformity:</td>
        </tr>
        <tr>
            <td colspan="2"><textarea readonly>{{ $ncr->deskripsi_ketidaksesuaian }}</textarea></td>
        </tr>
        <tr>
            <td style="width: 50%;">Prepared by: {{ $ncr->writer }}</td>
            <td style="width: 50%;">Date: {{ $ncr->created_at }}</td>
        </tr>
        <tr>
            <td style="width: 50%;">Reviewed by: </td>
            <td style="width: 50%;">Date: </td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="4">Non conformity rated as:</td>
        </tr>
        <tr>
            <td>
                <div class="checkbox-container">
                    <input type="radio" id="satisfactory">
                    <label for="satisfactory">Satisfactory</label>
                </div>
            </td>
            <td>
                <div class="checkbox-container">
                    <input type="radio" id="need_refinement">
                    <label for="need_refinement">Need Refinement</label>
                </div>
            </td>
            <td>
                <div class="checkbox-container">
                    <input type="radio" id="need_improvement">
                    <label for="need_improvement">Need Improvement</label>
                </div>
            </td>
            <td>
                <div class="checkbox-container">
                    <input type="radio" id="need_immediate_action">
                    <label for="need_immediate_action">Need Immediate Action</label>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <div class="checkbox-container">
                    <input type="radio" id="quality_issue">
                    <label for="quality_issue">Quality Issue</label>
                </div>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="2">Determination of Root Cause/s:</td>
        </tr>
        <tr>
            <td colspan="2"><textarea>... (Data Root Cause) ...</textarea></td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="2">Corrective/ Preventive Action/s:</td>
        </tr>
        <tr>
            <td colspan="2"><textarea>... (Data Corrective Action) ...</textarea></td>
        </tr>
        <tr>
            <td style="width: 50%;">Date of Completion: ... (Data Tanggal Selesai) ...</td>
            <td style="width: 50%;">Responsibility/PIC: ... (Data PIC) ...</td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="2">Follow up and Review Comments:</td>
        </tr>
        <tr>
            <td colspan="2"><textarea>... (Data Review Comments) ...</textarea></td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="4">Verification of Preventive-Corrective Action / Close Out:</td>
        </tr>
        <tr>
            <td style="width: 25%;">Date: ... (Data Verifikasi) ...</td>
            <td style="width: 25%;">Close out comment: ... (Data Komentar Close Out) ...</td>
            <td style="width: 25%;">Auditee/Team: ... (Data Auditee/Team) ...</td>
            <td style="width: 25%;">Auditor/MR: ... (Data Auditor/MR) ...</td>
        </tr>
        <tr>
            <td colspan="4" class="verification-section">
                <div class="checkbox-container">
                    <input type="checkbox" id="effective">
                    <label for="effective">Effective</label>
                </div>
                <div class="checkbox-container">
                    <input type="checkbox" id="not_effective">
                    <label for="not_effective">Not effective</label>
                </div>
            </td>
        </tr>
    </table>

</body>

</html>