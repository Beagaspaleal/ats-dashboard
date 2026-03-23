import { ksToast } from "./public/app";
import { initPipeline } from "./public/dragndrop";
import { openJobModal, openCandidateModal, initJobStateUpdate, initCandidateStateUpdate } from './public/modal';
import { initJobDashboard } from "./public/selectbox";

document.addEventListener('DOMContentLoaded', () => {
  ksToast();
  const btnJob = document.getElementById('btn-job');
  const btnCandidate = document.getElementById('btn-candidate');

  if (btnJob) {
      btnJob.addEventListener('click', openJobModal);
  }

  if (btnCandidate) {
      btnCandidate.addEventListener('click', openCandidateModal);
  }

  initJobStateUpdate();
  initCandidateStateUpdate();
  initPipeline();
  initJobDashboard();
});



