use std::sync::{Arc, mpsc};
use std::sync::atomic::{AtomicBool, Ordering};
use std::time::Duration;

use crate::brute_force::BruteForceAlgorithm;
use crate::christofides::ChristofidesSolver;
use crate::tsp::{ResolverTSP, TSPMatrix, TSPSolution};

mod brute_force;
mod christofides;

mod data;
mod tsp;

struct BenchmarkResult {
    name: String,
    solution: TSPSolution,
    duration: Duration,
}

impl BenchmarkResult {
    fn print(&self, x: &TSPMatrix) {
        let error = self.solution.total_distance - x.best_solution;
        let error_pct = error as f64 / x.best_solution as f64;
        println!(
            "{}\
            \tDataset: {}\
            \tTime:{:?}\
            \tSolution:{}\
            \tError: {:.1}%({})\
            \tTimeout: {}",
            self.name, x.dataset, self.duration, self.solution.total_distance, error_pct * 100., error, self.solution.forced_stop
        );
    }
}

/*
 * Return benchmark result or None if timeout exeeded
 */
fn benchmark<T: ResolverTSP>(name: String, timeout: Duration, dataset: usize) -> BenchmarkResult {
    let (sender, receiver) = mpsc::channel();
    let keep_executing = Arc::new(AtomicBool::new(true));
    let keep_executing_thread = keep_executing.clone();
    let name_thread = name.clone();

    let matrix = data::loader::load_tsp_matrix(dataset);
    let matrix_reference = data::loader::load_tsp_matrix(dataset);
    // To set a timeout we must spawn a new thread
    std::thread::spawn(move || {
        let start = std::time::Instant::now();
        let solution = T::resolve(matrix, keep_executing_thread);
        let result = BenchmarkResult {
            solution,
            name: name_thread,
            duration: start.elapsed(),
        };
        match sender.send(result) {
            Ok(_) => {}
            Err(_) => {}
        }
    });

    let received_value = receiver.recv_timeout(timeout);


    // Timeout
    let result = if received_value.is_err() {
        // Notifies to stop executing
        keep_executing.store(false, Ordering::Relaxed);
        let aborted_result = receiver.recv_timeout(Duration::from_secs(1));
        aborted_result.expect("Failed to abort by timeout!")
    } else {
        received_value.unwrap()
    };


    // Print result
    result.print(&matrix_reference);
    return result;
}

fn run_benchmarks<T: ResolverTSP>(name: String) {
    let timeout = Duration::from_secs(60*20);
    for i in 1..6 {
        benchmark::<T>(name.clone(), timeout.clone(), i);
    }
}


fn main() {
    run_benchmarks::<BruteForceAlgorithm>("Brute Force".to_string());
    run_benchmarks::<ChristofidesSolver>("Christofides".to_string());
}
