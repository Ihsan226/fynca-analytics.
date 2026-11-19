<?php

namespace App\Controllers;

use App\Models\SupportTicketModel;
use App\Models\TicketReplyModel;

class Tickets extends BaseController
{
    protected $ticketModel;
    protected $replyModel;

    public function __construct()
    {
        $this->ticketModel = new SupportTicketModel();
        $this->replyModel = new TicketReplyModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $tickets = $db->table('support_tickets st')
            ->select('st.*, u.nama_lengkap as sender_name')
            ->join('users u', 'u.id_user = st.id_user', 'left')
            ->orderBy('st.created_at', 'DESC')
            ->get()->getResultArray();

        $data = [
            'title'   => 'Support Tickets',
            'tickets' => $tickets
        ];
        return view('tickets/index', $data);
    }

    public function detail($id)
    {
        $db = \Config\Database::connect();
        
        $ticket = $db->table('support_tickets st')
            ->select('st.*, u.nama_lengkap as sender_name, u.role as sender_role')
            ->join('users u', 'u.id_user = st.id_user', 'left')
            ->where('st.id', $id)
            ->get()->getRowArray();

        if (!$ticket) {
            return redirect()->to('/tickets')->with('error', 'Tiket tidak ditemukan.');
        }

        $replies = $db->table('ticket_replies tr')
            ->select('tr.*, u.nama_lengkap as replier_name, u.role as replier_role')
            ->join('users u', 'u.id_user = tr.id_user', 'left')
            ->where('tr.ticket_id', $id)
            ->orderBy('tr.created_at', 'ASC')
            ->get()->getResultArray();

        $data = [
            'title'   => 'Detail Tiket #' . $ticket['id'],
            'ticket'  => $ticket,
            'replies' => $replies
        ];

        return view('tickets/detail', $data);
    }

    public function reply($id)
    {
        $rules = [
            'message' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Pesan balasan tidak boleh kosong.');
        }

        $this->replyModel->save([
            'ticket_id' => $id,
            'id_user'   => session()->get('id_user'),
            'message'   => $this->request->getPost('message')
        ]);

        // Update ticket status to in_progress if it was open
        $ticket = $this->ticketModel->find($id);
        if ($ticket['status'] == 'open') {
            $this->ticketModel->update($id, ['status' => 'in_progress']);
        }

        return redirect()->back()->with('success', 'Balasan berhasil dikirim.');
    }

    public function resolve($id)
    {
        $this->ticketModel->update($id, ['status' => 'resolved']);
        return redirect()->back()->with('success', 'Tiket berhasil ditutup/diselesaikan.');
    }
}
